<?php

namespace App\Http\Repository;

use App\Events\NewPersonalJobMessageSent;
use App\Models\{PersonalJobChat};
use Illuminate\Support\Facades\DB;

class PersonalJobChatRepository
{

    public function getAllMessages($personalJobId)
    {
        $messages = PersonalJobChat::with('sender')
        ->where('personal_job_id', $personalJobId)
        ->orderBy('created_at', 'asc')
        ->get();

        return ['success' => true , 'msg' => 'Messages Fetched Successfully' , 'data' => $messages];

    }

    public function storeMessage($request)
    {

        $message = PersonalJobChat::create([
            'personal_job_id' => $request->personal_job_id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        broadcast(new NewPersonalJobMessageSent($message))->toOthers();

        return ['success' => true , 'msg' => 'Message sent Successfully' , 'data' => $message->load('sender')];

    }
}
