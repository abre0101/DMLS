<?php
namespace App\Jobs;

use App\Models\WorkflowStep;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendApprovalReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = Carbon::now();

        // Send reminders for steps pending more than 24 hours
        $steps = WorkflowStep::where('status', 'pending')
            ->where('created_at', '<', $now->subHours(24))
            ->get();

        foreach ($steps as $step) {
            Notification::create([
                'user_id' => $step->approver_id,
                'message' => "Reminder: Approval pending for workflow '{$step->workflow->name}' (Step #{$step->step_order}).",
            ]);

            // Optional: escalate if more than 48 hours
            if ($step->created_at < $now->subHours(48)) {
                $manager = $step->approver->manager; // Assumes `manager()` relationship on User

                if ($manager) {
                    Notification::create([
                        'user_id' => $manager->id,
                        'message' => "Escalation: Approval delay in workflow '{$step->workflow->name}' (Step #{$step->step_order}).",
                    ]);
                }
            }
        }
    }
}
