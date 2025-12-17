<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }



    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Document Approved')
        ->line('Your document has been approved.')
        ->action('View Document', url('/documents/' . $this->document->id));
}
public function approveDocument(Document $document)
{
    $document->update(['status' => 'approved']);
    $document->user->notify(new DocumentApproved($document));

    return redirect()->route('admin.pendingApprovals');
}

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
