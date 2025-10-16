<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Letter; // ✅ This is the correct line
use App\Models\User;

class LetterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $letter;
    public $sender;

    public function __construct(Letter $letter, User $sender)
    {
        $this->letter = $letter;
        $this->sender = $sender;
    }

    public function build()
    {
        return $this->subject('New Letter')
                    ->view('emails.letter');
    }
}
