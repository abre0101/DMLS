<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<int, class-string|string>
     */
    protected $commands = [
        \App\Console\Commands\SendApprovalReminders::class,
        // Add other commands here as needed
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
   
    {protected function schedule(Schedule $schedule): void

{
    $schedule->job(new \App\Jobs\SendApprovalReminders)->hourly();


    $schedule->call(function () {
        $pendingSteps = WorkflowStep::where('status', 'pending')
            ->where('created_at', '<', now()->subDays(2))
            ->get();

        foreach ($pendingSteps as $step) {
            // Send reminder
            Notification::create([
                'user_id' => $step->approver_id,
                'message' => "Reminder: You have a pending workflow approval: {$step->workflow->name}",
            ]);

            // Escalation logic (e.g., notify admin if >3 days)
            if ($step->created_at < now()->subDays(3)) {
                Notification::create([
                    'user_id' => 1, // Admin ID
                    'message' => "Escalation: Workflow {$step->workflow->name} is delayed at step {$step->step_order}",
                ]);
            }
        }
    })->hourly();
}


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // Load commands from routes/console.php if any
        $this->load(__DIR__.'/Commands');

        // You can also require additional console routes here
        // require base_path('routes/console.php');
    }
}