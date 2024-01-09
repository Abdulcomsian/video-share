<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{ Files , ShareFolderFiles};

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

        $file = Files::where('id' , $request->file_id)->count();

        if($file == 0){
            return response()->json(['success' => false , 'msg' => 'Something Went Wrong' , 'error' => 'No File Found With This Id']);
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

        $file = ShareFolderFiles::where('id' , $request->file_id)->count();

        if($file == 0){
            return response()->json(['success' => false , 'msg' => 'Something Went Wrong' , 'error' => 'No File Found With This Id']);
        }

        return \Helper::addComment('App\Models\ShareFolderFiles' , $request->file_id , $request->comment_text);
    }
}
