<?php

namespace App\Listeners;

use App\Events\SendEmailEvent;
use App\Jobs\SendEmailJob;
use App\Mail\SendMailable;
use Carbon\Carbon;

class SendEmailListener
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
        $emailJob = (new SendEmailJob($event->emailData))->delay(Carbon::now()->addSeconds(2));
        dispatch($emailJob);
    }
}
