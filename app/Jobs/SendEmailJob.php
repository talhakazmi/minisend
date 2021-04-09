<?php

namespace App\Jobs;

use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailData = $this->emailData;
        $status = 'sent';
        try {
            \Mail::send([], [], function ($message) use ($emailData) {
                $message->from($emailData['sender']);
                $message->to($emailData['receiver']);
                $message->subject($emailData['subject']);
                if ($emailData['content'] != strip_tags($emailData['content'])) {
                    $message->setBody($emailData['content'], 'text/html');
                } else {
                    $message->setBody($emailData['content']);
                }

                if (isset($emailData['attachments'])) {
                    $attachments = json_decode($emailData['attachments'], true);
                    foreach ($attachments as $attachment) {
                        $message->attach($attachment);
                    }
                }
            });
        } catch (\Exception $exception) {
            $status = 'failed';
        }
        Email::where('uuid',$emailData['uuid'])->update(['status' => $status]);
    }
}
