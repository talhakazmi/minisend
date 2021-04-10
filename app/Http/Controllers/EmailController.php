<?php

namespace App\Http\Controllers;

use App\Events\SendEmailEvent;
use App\Http\Requests\SendEmailRequest;
use App\Models\Email;
use App\Services\FileManager\FileManagerInterface;
use App\Traits\FileHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class EmailController extends Controller
{
    use FileHandler;

    /**
     * Send email API endpoint
     *
     * @param SendEmailRequest $request
     * @param FileManagerInterface $fileManager
     */
    public function send(SendEmailRequest $request)
    {
        $data = $request->all();
        // Handle attachment if any
        if ($request->has('attachments')) {
            if (is_array($request->file('attachments'))) {
                foreach ($request->file('attachments') as $attachment) {
                    $file = $attachment;
                    $fileNameNew = $this->verifyAndStoreImage($file, 'email/attachments/');
                    $fileNames[] = storage_path('app/' . $this->fileManager->getPath('public/email/attachments/' . $fileNameNew));
                }
            } else {
                $file = $request->file('attachments');
                $fileNameNew = $this->verifyAndStoreImage($file, 'email/attachments/');
                $fileNames[] = storage_path('app/' . $this->fileManager->getPath('public/email/attachments/' . $fileNameNew));
            }
            $data['attachments'] = json_encode($fileNames);
        }

        // Create and Insert UUID to track Record in DB for status updating
        $data['uuid'] = md5($request->get('sender') . $request->get('receiver') . now());

        // Event to Fire with email data
        event(new SendEmailEvent($data));

        return Response::json(['message' => 'Email Send Successfully', 'code' => 201]);
    }

    /**
     * List all email records
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $email = Email::query();

        if($request->get('subject')){
            $subject = $request->get('subject');
            $email->where('subject', 'like', "%$subject%");
        }
        if($request->get('receiver')){
            $receiver = $request->get('receiver');
            $email->where('receiver', 'like', "%$receiver%");
        }
        if($request->get('sender')){
            $sender = $request->get('sender');
            $email->where('sender', 'like', "%$sender%");
        }
        $emails = $email->paginate(10);
        return Inertia::render('Email/Index', [
            'emails' => $emails->all(),
            'pagination' => $emails->toArray()
        ]);
    }

    /**
     * Show detail of single email
     *
     * @param $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        return Inertia::render('Email/Detail', [
            'email' => Email::find($id)
        ]);
    }

    /**
     * Load Inertia View to send new Email
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Email/Add');
    }
}
