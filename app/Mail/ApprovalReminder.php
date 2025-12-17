<?php
namespace App\Mail;

use App\Models\ApprovalHierarchy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovalReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $approval;

    public function __construct(ApprovalHierarchy $approval)
    {
        $this->approval = $approval;
    }

    public function build()
    {
        return $this->subject('Pending Approval Reminder')
                    ->view('emails.approval_reminder'); // Create this view
    }
}