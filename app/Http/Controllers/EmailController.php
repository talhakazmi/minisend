<?php

namespace App\Http\Controllers;

use App\Events\SendEmailEvent;
use App\Http\Requests\SendEmailRequest;
use App\Services\FileManager\FileManagerInterface;
use App\Traits\FileHandler;

class EmailController extends Controller
{
    use FileHandler;

    /**
     * Send email API endpoint
     *
     * @param SendEmailRequest $request
     * @param FileManagerInterface $fileManager
     */
    public function send(SendEmailRequest $request, FileManagerInterface $fileManager)
    {
        $data = $request->all();
        // Handle attachment if any
        if($request->has('attachments')) {
            if(is_array($request->file('attachments'))) {
                foreach($request->file('attachments') as $attachment) {
                    $file = $attachment;
                    $fileNameNew = $this->verifyAndStoreImage($file, 'email/attachments/');
                    $fileNames[] = storage_path('app/'.$this->fileManager->getPath('public/email/attachments/' . $fileNameNew));
                }
            } else {
                $file = $request->file('attachments');
                $fileNameNew = $this->verifyAndStoreImage($file, 'email/attachments/');
                $fileNames[] = storage_path('app/'.$this->fileManager->getPath('public/email/attachments/' . $fileNameNew));
            }
            $data['attachments'] = json_encode($fileNames);
        }

        // Create and Insert UUID to track Record in DB for status updating
        $data['uuid'] = md5($request->get('sender').$request->get('receiver').now());

        // Event to Fire with email data
        event(new SendEmailEvent($data));
    }
}
