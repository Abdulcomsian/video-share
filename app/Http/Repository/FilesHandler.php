<?php

namespace App\Http\Repository;
use File;
use Illuminate\Support\Facades\Storage;
use App\Models\{ Files , Folder, PersonalJob , ShareFolderFiles};
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
            $fileList = [];
            $videoExtension = ['mp4' , 'webm'];
            $thumbnails = json_decode($request->thumbnail);
            // dd("inside file");

            $folder = Folder::find($folderId);

            if($request->file('files') && count($request->file('files')))
            {
                foreach($request->file('files') as $index => $file){

                    $fileName = $file->getClientOriginalName();
                    $extension = $file->extension();
                    $name = time() . "-" . $fileName;
                    
                    //Storage::disk('s3')->put($folder->name.'/'.$name, file_get_contents($file));     
                    $check = $this->aws->uploadMedia( $folder->name , $name , $file);
                    if($check['success']){
                        $type =in_array($extension , $videoExtension) ? 1 : 2;
                        $thumbnailName  = null;

                        if( isset($thumbnails[$index]) && !is_null($thumbnails[$index]) ){
                            $imageUrl = substr($thumbnails[$index], strpos($thumbnails[$index], ',') + 1);
                            $image = base64_decode($imageUrl);
                            $thumbnailName = time().$index."-job-file-thumbnail".".png";
                            file_put_contents(public_path()."/uploads/".$thumbnailName , $image);
                        }

                        $fileList[] = ['folder_id' => $folderId , 'type' => $type , 'path' => $name , 'extension' => $extension , "thumbnail" => $thumbnailName];
                    }
                
                }
                
                Files::insert($fileList);    
            }
    
            return ["success" => true , "msg" => "Files Added Successfully"];

        }catch(\Exception $e){
            return response()->json(['success' => false , "msg" => $e->getMessage()]);
        }
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
                    $file->delete(); 
                }
                return $check;
                //file path in s3
                // if(Storage::disk('s3')->exists($fileName))
                // {
                //     Storage::disk('s3')->delete($fileName);
                //     $file->delete();
                //     return ["success" => true , "msg" => "File Deleted Successfully"];
        
                // }else{
                //     return ["success" => false , "msg" => "File Not Found"];
                // }
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
            // dd("inside file");

            $folder = $personalJob->folder;

            if($request->file('files') && count($request->file('files')))
            {
                foreach($request->file('files') as $index => $file){
                    $fileName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $name = time() . "-" . $fileName;
                    // $check = Storage::disk('s3')->put($folder->name.'/'.$name, file_get_contents($file));     
                    // dd($folder->name , $name );
                    // dd($file);
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

        $bucketName = config('filesystems.disks.s3.bucket');
        
        $folderPath = $file->folder->name;
        
        $bucketAddress = "https://$bucketName.s3.amazonaws.com/".$folderPath;

        $thumbnailPath = public_path('uploads');
        
        return response()->json(['success' => true ,  'file'  => $file , 'bucketAddress' => $bucketAddress , 'thumbnailPath' => $thumbnailPath]);
    
    }

    public function getShareFile($request){

        $file = ShareFolderFiles::with('comments' , 'folder')->where('id' , $request->file_id)->first();

        $bucketName = config('filesystems.disks.s3.bucket');
        
        $folderPath = $file->folder->name;
        
        $bucketAddress = "https://$bucketName.s3.amazonaws.com/".$folderPath;

        $thumbnailPath = public_path('uploads');
        
        return response()->json(['success' => true ,  'file'  => $file , 'bucketAddress' => $bucketAddress , 'thumbnailPath' => $thumbnailPath]);
    
    }

}