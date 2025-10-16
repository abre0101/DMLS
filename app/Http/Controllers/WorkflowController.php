<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Models\Report;
use App\Models\Notification;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WorkflowController extends Controller
{
    /**
     * Approve a workflow step (with hierarchy and e-signature support).
     */
    public function approveStep(Request $request, $stepId)
    {
        $step = WorkflowStep::findOrFail($stepId);

        if (Auth::id() !== $step->approver_id) {
            abort(403, 'Not authorized to approve this step.');
        }

        // Validate e-signature
        $request->validate([
            'signature' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        // Check previous step (approval hierarchy)
        $prevStep = WorkflowStep::where('workflow_id', $step->workflow_id)
            ->where('step_order', '<', $step->step_order)
            ->where('status', '!=', 'approved')
            ->first();

        if ($prevStep) {
            return back()->with('error', 'Previous approval step not completed.');
        }

        // Approve step
        $step->update([
            'status' => 'approved',
            'approved_at' => now(),
            'signature' => $request->signature,
            'comment' => $request->comment,
        ]);

        // Notify next approver
        $nextStep = WorkflowStep::where('workflow_id', $step->workflow_id)
            ->where('step_order', '>', $step->step_order)
            ->orderBy('step_order')
            ->first();

        if ($nextStep) {
            Notification::create([
                'user_id' => $nextStep->approver_id,
                'message' => "You have a pending approval for workflow: {$step->workflow->name}",
            ]);

            // Pseudo-hook: Schedule reminder & escalation (implement in queue/scheduler)
        } else {
            // All steps approved → mark workflow approved
            $step->workflow->update(['status' => 'approved']);

            Report::create([
                'title' => "{$step->workflow->document->title} Approved",
                'description' => "The document '{$step->workflow->document->title}' was approved through workflow.",
                'status' => 'approved',
                'generated_by' => Auth::id(),
                'document_id' => $step->workflow->document_id,
            ]);
        }

        return back()->with('success', 'Step approved successfully.');
    }

    /**
     * Show list of workflows.
     */
    public function index(Request $request)
    {
        $query = Workflow::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $workflows = $query->withCount('steps')->paginate(10);

        return view('director.workflows', compact('workflows'));
    }

    /**
     * Store new workflow and steps (hierarchy setup).
     */
    public function showStepApprovalForm($stepId)
{
    $step = WorkflowStep::with('workflow')->findOrFail($stepId);
    return view('workflow.step_approval', compact('step'));
}


public function submitStepApproval(Request $request, $stepId)
{
    $request->validate([
        'signature' => 'required|string|max:255',
    ]);

    $step = WorkflowStep::findOrFail($stepId);

    if (auth()->id() !== $step->approver_id) {
        abort(403, 'Unauthorized');
    }

    // Check prior steps
    $prevStep = WorkflowStep::where('workflow_id', $step->workflow_id)
        ->where('step_order', '<', $step->step_order)
        ->where('status', '!=', 'approved')
        ->first();

    if ($prevStep) {
        return back()->with('error', 'Previous step not yet approved.');
    }

    $step->update([
        'status' => 'approved',
        'approved_at' => now(),
        'signature' => $request->input('signature'),
    ]);

    // Notify next approver
    $nextStep = WorkflowStep::where('workflow_id', $step->workflow_id)
        ->where('step_order', '>', $step->step_order)
        ->orderBy('step_order')->first();

    if ($nextStep) {
        Notification::create([
            'user_id' => $nextStep->approver_id,
            'message' => "Approval needed for workflow: {$step->workflow->name}",
        ]);
    } else {
        $step->workflow->update(['status' => 'approved']);
    }

    return redirect()->route('workflow.steps')->with('success', 'Step approved.');
}

public function listSteps()
{
    $userId = auth()->id();

    $steps = WorkflowStep::with(['workflow', 'approver'])
        ->where('approver_id', $userId)
        ->orderBy('step_order')
        ->get();

    return view('workflow.steps', compact('steps'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:pending,approved,rejected',
            'description' => 'nullable|string',
            'document_id' => 'required|exists:documents,id',
            'steps' => 'required|array',
            'steps.*.approver_id' => 'required|exists:users,id',
        ]);

        $workflow = Workflow::create($validated);

        // Create workflow steps (approval chain)
        foreach ($validated['steps'] as $index => $step) {
            WorkflowStep::create([
                'workflow_id' => $workflow->id,
                'step_order' => $index + 1,
                'approver_id' => $step['approver_id'],
            ]);
        }

        return redirect()->route('director.workflows')->with('success', 'Workflow created successfully with approval hierarchy.');
    }

    public function show(Workflow $workflow)
    {
        $workflow->load('steps.approver');
        return view('director.workflow_show', compact('workflow'));
    }

    public function update(Request $request, Workflow $workflow)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:pending,approved,rejected',
            'description' => 'nullable|string',
        ]);

        $workflow->update($validated);

        return redirect()->route('director.workflows')->with('success', 'Workflow updated successfully.');
    }

    public function destroy(Workflow $workflow)
    {
        $workflow->delete();

        return redirect()->route('director.workflows')->with('success', 'Workflow deleted successfully.');
    }

    public function reject($id)
    {
        $workflow = Workflow::with('document')->findOrFail($id);
        $workflow->update(['status' => 'rejected']);

        Report::create([
            'title' => "{$workflow->document->title} Rejected",
            'description' => "The document '{$workflow->document->title}' has been rejected.",
            'status' => 'rejected',
            'generated_by' => Auth::id(),
            'document_id' => $workflow->document_id,
        ]);

        return redirect()->route('director.workflows')->with('success', 'Workflow rejected successfully.');
    }
}
