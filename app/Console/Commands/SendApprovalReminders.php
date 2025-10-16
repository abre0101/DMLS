<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApprovalRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalReminderMail;

class SendApprovalReminders extends Command
{
    protected $signature = 'approval:send-reminders';
    protected $description = 'Send reminders for pending approvals and escalate if necessary';

    public function handle()
    {
        $pending = ApprovalRequest::where('status', ApprovalRequest::STATUS_PENDING)
                    ->whereNull('approved_at')
                    ->get();

        foreach ($pending as $approval) {
            // Send reminder email
            Mail::to($approval->approver->email)->send(new ApprovalReminderMail($approval));

            // Example escalation logic: if pending more than 3 days escalate
            if ($approval->created_at->diffInDays(now()) > 3) {
                // Determine who to escalate to (implement logic accordingly)
                // $nextApprover = ...

                // $approval->escalate($nextApprover);
            }
        }

        $this->info('Reminders and escalations handled successfully.');
    }
}