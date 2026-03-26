<?php

namespace App\Http\Repository;
use File;
use Illuminate\Support\Facades\Storage;
use App\Models\{ Files , Folder, PersonalJob , ShareFolderFiles, Comment};
use App\Http\Repository\AwsHandler;
class FilesHandler{

    protected $aws;

    function __construct(AwsHandler $aws)
    {
        $this->aws = $aws;
    }

    public function uploadMedia($request)
    {
        try{
            $folderId = $request->folder_id;
            $videoExtension = ['mp4' , 'webm'];

            $folder = Folder::find($folderId);

            if($request->hasFile('file'))
            {

                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $extension = $file->extension();
                $name = time() . "-" . $fileName;

                $check = $this->aws->uploadMedia( $folder->name , $name , $file);

                if(!$check['success']){
                    return response()->json(['success' => false , "msg" => 'something went wrong while adding file']);
                }

                $type =in_array($extension , $videoExtension) ? 1 : 2;
                $thumbnailName  = null;

                if( $request->hasFile('thumbnail') ){
                    $thumbnail = $request->file('thumbnail');
                    $thumbnailName = time().'-'.str_replace(" ", "_" , $request->filename);
                    $thumbnail->move(public_path('uploads') , $thumbnailName);
                }

                Files::create(['folder_id' => $folderId , 'type' => $type , 'path' => $name , 'extension' => $extension , "thumbnail" => $thumbnailName]);

                return ["success" => true , "msg" => "Files Added Successfully"];
            } else {
                return response()->json(['success' => false , "msg" => 'Please add file']);
            }

        }catch(\Exception $e){
            return response()->json(['success' => false , "msg" => $e->getMessage()]);
        }
    }

    public function directMediaUpload($request)
    {
        $folderId = $request->folderId;
        $videoExtension = ['mp4' , 'webm'];
        $name = $request->fileName;
        $extension = explode("." , $request->fileName)[1];
        $thumbnail = null;
        if($request->hasFile('thumbnail'))
        {
            $file = $request->file('thumbnail');
            $filename = $file->getClientOriginalName();
            $thumbnail = time().$filename;
            $file->move(public_path('uploads') , $thumbnail);
        }

        $type =in_array($extension , $videoExtension) ? 1 : 2;
        Files::create(['folder_id' => $folderId , 'type' => $type , 'path' => $name , 'extension' => $extension , "thumbnail" => $thumbnail]);
        return ["success" => true , "msg" => "Files Added Successfully"];
    }



    public function deleteMedia($request)
    {

        try{
            $fileId = $request->file_id;

            $file = Files::where('id' , $fileId)->first();

            if($file){
                $folder = Folder::find($file->folder_id);
                $fileName = $folder->name.'/'.$file->path;
                $check = $this->aws->deleteMedia($fileName);
                if($check['success']){
                    Files::where('id' , $fileId)->delete();
                    return ["success" => true , "msg" => "File Deleted Successfully"];
                } else {
                    return ["success" => false , "msg" => "Something went wrong while deleting file"];
                }
            }else{
                return ["success" => false , "msg" => "File Not Found"];
            }

        }catch(\Exception $e){
            return ["success" => false , "msg" => $e->getMessage()];
        }


    }


    public function uploadShareFolderFiles($request)
    {
        try{
            $jobId = $request->job_id;
            $personalJob = PersonalJob::with('folder')->where('id' , $jobId)->first();
            $folderId = $personalJob->folder->id;
            $fileList = [];
            $videoExtension = ['mp4' , 'webm'];
            $thumbnails = json_decode($request->thumbnail);

            $folder = $personalJob->folder;

            if($request->file('files') && count($request->file('files')))
            {
                foreach($request->file('files') as $index => $file){
                    $fileName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $name = time() . "-" . $fileName;
                    $check = $this->aws->uploadMedia($folder->name , $name , $file );

                    if($check['success']){

                        $type =in_array($extension , $videoExtension) ? 1 : 2;
                        $thumbnailName  = null;
                        if( isset($thumbnails[$index]) && !is_null($thumbnails[$index]) ){
                            $imageUrl = substr($thumbnails[$index], strpos($thumbnails[$index], ',') + 1);
                            $image = base64_decode($imageUrl);
                            $thumbnailName = time().$index."-share-file-thumbnail".".png";
                            file_put_contents(public_path()."/uploads/".$thumbnailName , $image);
                        }

                        $fileList[] = ['share_folder_id' => $folderId , 'type' => $type , 'path' => $name , 'extension' => $extension , "thumbnail" => $thumbnailName ];
                    }
                }

                ShareFolderFiles::insert($fileList);
            }

            return ["success" => true , "msg" => "Files Added Successfully"];

        }catch(\Exception $e){
            return response()->json(['success' => false , "msg" => $e->getMessage()]);
        }

    }


    public function uploadDbShareFolderFiles($request , $shareFolder)
    {
       $videoExtension = ['mp4' , 'webm'];
       $name  = $request->fileName;
       $extension = explode(".",$request->fileName)[1];
       $type =in_array($extension , $videoExtension) ? 1 : 2;
       $thumbnail = null;

       if($request->hasFile('thumbnail'))
        {
            $file = $request->file('thumbnail');
            $filename = $file->getClientOriginalName();
            $thumbnail = time().$filename;
            $file->move(public_path('uploads') , $thumbnail);
        }

        $comment = $request->comment ?? null;
       $shareFile =  ['share_folder_id' => $shareFolder->id , 'type' => $type , 'path' => $name , 'extension' => $extension , "thumbnail" => $thumbnail, 'comment' => $comment ];
       $shareFolderFile = ShareFolderFiles::create($shareFile);

       if(!empty($comment)){

            Comment::create([
                'commentable_type' => ShareFolderFiles::class,
                'commentable_id' => $shareFolderFile->id,
                'comment_text' => $comment,
                'user_id' => auth()->user()->id
            ]);
       }

       return ["success" => true , "msg" => "Files Added Successfully"];
    }


    public function deleteShareMedia($request)
    {
        try{
            $fileId = $request->file_id;
            $file = ShareFolderFiles::with('folder')->where('id' , $fileId)->first();
            $fileName = $file->folder->name.'/'.$file->path;

            //file path in s3
            if(Storage::disk('s3')->exists($fileName))
            {
                Storage::disk('s3')->delete($fileName);

                $file->delete();

                return ["success" => true , "msg" => "File Deleted Successfully"];

            }else{
                return ["success" => false , "msg" => "File Not Found"];
            }

        }catch(\Exception $e){
            return ["success" => false , "msg" => $e->getMessage()];
        }

    }

    public function getFile($request){

        $file = Files::with('comments' ,'folder')->where('id' , $request->file_id)->first();

        if(!$file){
            return ['success' => false , 'msg' => 'Something Went Wrong' , 'error' => 'No File Found With This Id' ];
        }

        $bucketName = config('filesystems.disks.s3.bucket');

        $folderPath = $file->folder->name;

        $bucketAddress = "https://$bucketName.s3.amazonaws.com/".$folderPath;

        $thumbnailPath = public_path('uploads');

        return ['success' => true ,  'file'  => $file , 'bucketAddress' => $bucketAddress , 'thumbnailPath' => $thumbnailPath];

    }

    public function getShareFile($request){

        $file = ShareFolderFiles::with('comments' , 'folder')->where('id' , $request->file_id)->first();

        if(!$file){
            return ['success' => false , 'msg' => 'Something Went Wrong' , 'error' => 'No file Exist With This Id'];
        }

        $bucketName = config('filesystems.disks.s3.bucket');

        $folderPath = $file->folder->name;

        $bucketAddress = "https://$bucketName.s3.amazonaws.com/".$folderPath;

        $thumbnailPath = public_path('uploads');

        return ['success' => true ,  'file'  => $file , 'bucketAddress' => $bucketAddress , 'thumbnailPath' => $thumbnailPath];

    }

}
