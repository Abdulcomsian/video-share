<?php

namespace App\Http\Controllers;

use App\Http\Repository\AwsHandler;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PresignedUrlController extends Controller
{
    protected $aws;

    public function __construct(AwsHandler $aws)
    {
        $this->aws = $aws;
    }

    public function generateUploadUrl(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'folder_id' => 'required|integer|exists:folders,id',
                'file_name' => 'required|string|max:500',
                'content_type' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Invalid parameters',
                    'error' => $validator->getMessageBag(),
                ], 400);
            }

            $folder = Folder::findOrFail($request->folder_id);
            $fileName = time() . '-' . $request->file_name;

            $presignedUrl = $this->aws->generatePresignedUploadUrl(
                $folder->name,
                $fileName,
                $request->content_type
            );

            return response()->json([
                'success' => true,
                'presigned_url' => $presignedUrl,
                'file_name' => $fileName,
                'key' => $folder->name . '/' . $fileName,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
