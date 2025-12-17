<?php
namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('assigned_to', Auth::id())->latest()->paginate(10);
        return view('manager.tasks.index', compact('tasks'));
    }

  public function assignedByMe()
{
    $tasks = Task::where('created_by', Auth::id())->latest()->paginate(10);
    return view('manager.tasks.assignedme', compact('tasks'));
}
public function assignedToMe()
{
    $tasks = auth()->user()->tasks()->with('assignedBy')->orderBy('due_date')->get();

    return view('employee.tasks.assigned_to_me', compact('tasks'));
}


  public function tasksAssignedToMe()
    {
        $user = Auth::user();

       
        $tasks = Task::where('assigned_to', $user->id)
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('employee.tasks.assigned_to_me', compact('tasks'));
    }
        public function complete(Task $task)
    {
        $task->status = 'completed';
        $task->save();

        return redirect()->back()->with('success', 'Task marked as completed!');
    }
public function create()
{

$employees = User::where('role_id',4 )->get();
return view('manager.tasks.create', compact('employees'));

}
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        Task::create([
            ...$validated,
            'status' => Task::STATUS_PENDING,
             'created_by' => Auth::id(),
        ]);

        return redirect()->route('manager.tasks.index')->with('success', 'Task created.');
    }
        public function show(Task $task)
    {
        
        return view('manager.tasks.show', compact('task'));
    }
    public function updateStatus(Request $request, Task $task)
{
    $request->validate([
        'status' => 'required|in:pending,in_progress,completed,cancelled',
    ]);

    $task->status = $request->status;
    $task->save();

    return redirect()->back()->with('success', 'Task status updated.');
}

}
