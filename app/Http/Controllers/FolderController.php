<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\FolderHandler;
use Illuminate\Support\Facades\Validator;

class FolderController extends Controller
{
    protected $folderHandler;

    public function __construct(FolderHandler $folderHandler)
    {
        $this->folderHandler = $folderHandler;
    }

    public function createClientFolder(Request $request)
    {
        try{
            $validator = Validator::make($request->all() ,[ 
                "name" => "required|string"
            ]);
            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" , "error" => $validator->getMessageBag()]);
            }else{

                $response = $this->folderHandler->createClientFolder($request);
                return response()->json($response);
            }

        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()]);

        }

    }







}
