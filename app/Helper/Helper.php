<?php

namespace App\Helper;
use App\Models\Comment;

class Helper{

    public static function addComment($commentable_type , $commentable_id , $comment_text)
    {
        try{
            Comment::create([
                'commentable_type' => $commentable_type,
                'commentable_id' => $commentable_id,
                'comment_text' => $comment_text,
                'user_id' => auth()->user()->id
            ]);

            return response()->json(["success" =>true , 'msg' => 'Comment Added Successfully']);
        }catch(\Exception $e){
            return response()->json(['success' => false , 'msg' => 'Something Went Wrong' , 'error' => $e->getMessage()] , 400);
        }
    }
    public static function getProposalStatus($status)
    {
        $statusLabels = [
            0 => 'unawarded',
            1 => 'awarded',
            2 => 'cancelled',
            3 => 'completed',
        ];

        $label = $statusLabels[$status] ?? 'Unknown';

        return $label;

    }

}
