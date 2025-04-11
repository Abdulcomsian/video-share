<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\{PersonalJobChatRepository};
use Illuminate\Support\Facades\Validator;

class PersonalJobChatController extends Controller
{

    protected $repository;

    public function __construct(PersonalJobChatRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index($personalJobId)
    {
        try {

            if ($personalJobId == '') {
                return response()->json(['success' => false, 'msg' => 'Job ID is require'], 400);
            }

            $data = $this->repository->getAllMessages($personalJobId);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'Something Went Wrong', 'error' => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'personal_job_id' => 'required|exists:personal_jobs,id',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'msg' => 'Something Went Wrong', 'error' => $validator->getMessageBag()], 400);
            } else {
                $response = $this->repository->storeMessage($request);
                return response()->json($response);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'Something Went Wrong', 'error' => $e->getMessage()], 400);
        }
    }
}
