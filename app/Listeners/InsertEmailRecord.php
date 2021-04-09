<?php

namespace App\Listeners;

use App\Events\SendEmailEvent;
use App\Models\Email;

class InsertEmailRecord
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendEmailEvent  $event
     * @return void
     */
    public function handle(SendEmailEvent $event)
    {
        $email = new Email();
        $email->uuid = $event->emailData['uuid'];
        $email->sender = $event->emailData['sender'];
        $email->receiver = $event->emailData['receiver'];
        $email->subject = $event->emailData['subject'];
        $email->content = $event->emailData['content'];
        $email->attachments = $event->emailData['attachments'];
        $email->save();
    }
}
