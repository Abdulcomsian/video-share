<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function addFileComment(Request $request){
        $validator = Validator::make($request->all() , [
            'file_id' => 'required|numeric',
            'comment_text' => 'required|string'
        ]);        

        if($validator->fails()){
            return response()->json(['success' => false , 'msg' => 'Something Went Wrong' , 'error' => $validator->getMessageBag()]);
        }

        return \Helper::addComment('App\Models\Files' , $request->file_id , $request->comment_text);
    }

    public function addShareFileComment(Request $request){
        $validator = Validator::make($request->all() , [
            'file_id' => 'required|numeric',
            'comment_text' => 'required|string'
        ]);        

        if($validator->fails()){
            return response()->json(['success' => false , 'msg' => 'Something Went Wrong' , 'error' => $validator->getMessageBag()]);
        }

        return \Helper::addComment('App\Models\ShareFolderFiles' , $request->file_id , $request->comment_text);
    }
}
