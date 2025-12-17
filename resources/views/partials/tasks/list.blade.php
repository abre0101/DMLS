@if ($tasks->count())
    <div class="tasks-list">
        @foreach ($tasks as $task)
            @php
                $statusConfig = [
                    'pending' => ['color' => '#6c757d', 'icon' => '⏸️', 'label' => 'Pending'],
                    'in_progress' => ['color' => '#0d6efd', 'icon' => '▶️', 'label' => 'In Progress'],
                    'completed' => ['color' => '#28a745', 'icon' => '✅', 'label' => 'Completed'],
                    'cancelled' => ['color' => '#dc3545', 'icon' => '❌', 'label' => 'Cancelled'],
                ];
                $status = $statusConfig[$task->status] ?? ['color' => '#6c757d', 'icon' => '❓', 'label' => 'Unknown'];
            @endphp
            
            <div class="task-card">
                <div class="task-header">
                    <div class="task-title">{{ $task->title }}</div>
                    <span class="task-status" style="background-color: {{ $status['color'] }};">
                        {{ $status['icon'] }} {{ $status['label'] }}
                    </span>
                </div>

                @if($task->description)
                    <div class="task-description">
                        {{ \Illuminate\Support\Str::limit($task->description, 80) }}
                    </div>
                @endif

                <div class="task-meta">
                    <div class="task-meta-item">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                        </svg>
                        <span>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No due date' }}</span>
                    </div>
                    <div class="task-meta-item">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        </svg>
                        <span>{{ $task->assignedBy->name ?? 'Unknown' }}</span>
                    </div>
                </div>

                @if ($task->status !== 'completed')
                    <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="task-form">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="task-select">
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>⏸️ Pending</option>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>▶️ In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state-compact">
        <div class="empty-icon-compact">📋</div>
        <p class="empty-text-compact">No tasks assigned</p>
    </div>
@endif

<style>
.tasks-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.task-card {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.task-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
}

.task-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
    line-height: 1.4;
}

.task-status {
    padding: 0.25rem 0.625rem;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
    white-space: nowrap;
    flex-shrink: 0;
}

.task-description {
    color: #495057;
    font-size: 0.85rem;
    line-height: 1.5;
    background: #f8f9fa;
    padding: 0.625rem;
    border-radius: 6px;
}

.task-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e9ecef;
}

.task-meta-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.task-form {
    margin-top: 0.25rem;
}

.task-select {
    width: 100%;
    padding: 0.5rem;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #2c3e50;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.task-select:hover {
    border-color: #667eea;
}

.task-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
</style>
