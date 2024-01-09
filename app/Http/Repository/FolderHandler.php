<?php


namespace App\Http\Repository;

use App\Models\{ ShareFolder , Files , Folder, PersonalJob, ShareFolderFiles};
use Illuminate\Support\Facades\Storage;
use App\Http\Repository\AwsHandler;
class FolderHandler
{
    protected $aws;

        function __construct(AwsHandler $aws)
        {
            $this->aws = $aws;
        }

        public function createFilesInFolder($request)
        {
            $folderId = $request->folder_id;
            $files = $request->file('files');
            $folder = Folder::findOrFail($folderId);
            $uploadedFiles = [];

            if ($files) {
                foreach ($files as $file)
                {
                    // Store each file in the folder
                    $path = Storage::putFile('folder_files/' . $folder->id, $file);
                    $extension = $file->getClientOriginalExtension();
                    // dd( $file->getClientMimeType());
                    // Create a new file record in the database
                    $createdFile = $folder->files()->create([
                        'folder_id' => $folder->id,
                        'path' => $path,
                        'extension' => $extension,
                        'type' => 1,
                    ]);

                    $uploadedFiles[] = [
                        "id" => $createdFile->id,
                        "path" => $createdFile->path,
                        "extension" => $createdFile->extension,
                        "type" => $createdFile->type,
                        "folder_id" => $createdFile->folder_id,
                    ];
                }
            }

            return [
                "success" => true,
                "msg" => "Files Uploaded Successfully",
                "folder_id" => $folder->id,
                "files" => $uploadedFiles,
            ];
        }


        public function createFolder($request)
        {
            try{
                $clientId = auth()->user()->id;
                $name  = $request->name;

                $folderName = trim(str_replace(" " , "-" , $name).'-'.$clientId);

                $folderCount = Folder::where('name' , $folderName)->count();

                if($folderCount == 0){

                   // $check = Storage::disk('s3')->makeDirectory($folderName);
                    $check = $this->aws->createFolder($folderName);
            
                    if($check['success'])
                    {
                        Folder::create([
                            "client_id" => $clientId,
                            "name"  => $folderName
                        ]);

                        return ["success" => true , "msg" => "Folder Created Successfully"];

                    }else{
                   
                        return response()->json(["success" => false , "msg" => "Error While Creating Folder"]);
                    }
                
                }else{
                    return response()->json(["success" => false , "msg" => "Folder Already Exist"]);
                
                }

                

            }catch(\Exception $e){

                return ["success" => false , "msg"=> $e->getMessage()];
            }
        }

        public function clientFolders()
        {
            $userId = auth()->user()->id;

            $folders = Folder::with('files')->where('client_id' , $userId)->get();

            return ["success" => true , "msg" => "Folder Fetched Successfully" , "folders" => $folders];
        }

        public function folderDetail($request)
        {
            $folderId = $request->folder_id;

            $folderDetail = Folder::with('client')->where('id' , $folderId)->first();

            return ["success" => true , "msg" => "Folder Detail Fetched Successfully" , "folderDetail" => $folderDetail];

        }

        public function deleteFolder($request)
        {
            $folderId = $request->folder_id;

            $runningJob = PersonalJob::where('folder_id' , $folderId)->whereIn('status' , ['awarded , Awarded'])->count();

            if($runningJob > 0){
                return ['success' => false , 'error' => "You can not delete the folder during a running job"];
            }

            $folder = Folder::where('id' , $folderId)->first();

            if($folder){
                //Storage::disk('s3')->deleteDirectory($folder->name);
                $check = $this->aws->deleteFolder($folder->name);
                
                if($check['success']){
    
                    $folder->delete();
                
                }
                return $check;
            }else{
                return ['success' => false , 'error' => "No Folder Found"];
            }
            
            
            
        }

        public function updateFolder($request)
        {
            $folderId = $request->folder_id;
            $name = $request->folder_name;
            $folder = Folder::where('id' , $folderId)->first();
            $clientId = auth()->user()->id;
            $folderName = str_replace(" ","-",$name)."-".$clientId;

            $folderCount = Folder::where('name' , $folderName)->count();

            if($folderCount == 0)
            {

                $check = $this->aws->updateFolder($folder->name , $folderName);

                if($check['success']){
                    $folder->name = $folderName;
                    $folder->save();
                }

                return $check;

                // $files = Storage::disk('s3')->allFiles($folder->name);

                // $newFolder = Storage::disk('s3')->makeDirectory($folderName);

                // if($newFolder){
                    
                //     foreach($files as $file)
                //     {
                //         $newKey = str_replace($folder->name, $folderName, $file);
                        
                //         Storage::disk('s3')->copy($file, $newKey);
                //     }

                //     Storage::disk('s3')->deleteDirectory($folder->name);

                //     $folder->name = $folderName;

                //     $folder->save();

                //     return ["success" => true , "msg" => "Folder Updated Successfully"];
                // }else{

                //     return ["success" => false , "msg" => "Something Went Wrong"];
                // }


                
            }else{
                return ["success" => false , "msg" => "Can't Change Folder Name Already Folder Exist With This Name"];
            }

    
            

        }

        public function folderList()
        {
            $folderList = Folder::with('client')->orderBy('id' , 'desc')->get();

            return ["success" => true , "folderList" => $folderList ];
        }

        public function getFiles($request)
        {
            $folderId = $request->folder_id;

            $bucketName = config('filesystems.disks.s3.bucket');
            
            $folder = Folder::find($folderId);
            
            $folderPath = $folder->name;
            
            $bucketAddress = "https://$bucketName.s3.amazonaws.com/".$folderPath;
            
            $files = Files::with('folder')->where('folder_id' , $folderId)->get();

            $thumbnailPath = public_path('uploads');



            // $filesList = Storage::disk('s3')->files($folderPath);

            // $files = array_map( function($file) use ($bucketAddress){
            //     return $bucketAddress.$file;
            // }, $filesList);

            // dd($files);

            return ["success" => true , "files" => $files , "bucket_address" => $bucketAddress , 'thumbnailPath' =>$thumbnailPath ];
        }


        public function checkShareFolder($jobId)
        {
           return ShareFolder::where('job_id' , $jobId)->first();
        }

        public function createShareFolder($jobId)
        {
            try{
                $editorId = auth()->user()->id;
                $jobId  = $jobId;

                $job = PersonalJob::where('id' , $jobId )->first();

                $jobTitle = $job->title;

                $folderName = trim(str_replace(" " , "-" , $jobTitle).'-'.$editorId).'-'.strtotime(date('Y-m-d H:i:s'));

                // $check = Storage::disk('s3')->makeDirectory($folderName);
                $check = $this->aws->createFolder($folderName);

                if($check['success'])
                {
                    ShareFolder::create([
                        "editor_id" => $editorId,
                        "job_id" => $jobId, 
                        "name"  => $folderName,
                    ]);

                    return ["success" => true , "msg" => "Folder Created Successfully"];

                }else{
                
                    return ["success" => false , "msg" => "Error While Creating Folder"];
                }


            }catch(\Exception $e){

                return ["success" => false , "msg"=> $e->getMessage()];
            }
        }


        public function getShareFolderFiles($request)
        {
            $jobId = $request->job_id;

            $bucketName = config('filesystems.disks.s3.bucket');
            
            $personalJob = PersonalJob::with('folder')->where('id' , $jobId)->first();

        if($personalJob->folder){
            
            $folder = $personalJob->folder;
            
            $folderPath = $folder->name;
            
            $bucketAddress = "https://$bucketName.s3.amazonaws.com/".$folderPath;
            
            $files = ShareFolderFiles::with('folder')->where('share_folder_id' , $folder->id)->get();

            $thumbnailPath = public_path('uploads');
           
            return ["success" => true , "files" => $files , "bucket_address" => $bucketAddress , 'thumbnailPath' => $thumbnailPath];
        
        }else{

            return ["success" => true ,  "msg"=>"Sorry, No Folder/Files Found" ];
        }
        
            

            
        }


        public function jobFolder($request)
        {
            $bucketName = config('filesystems.disks.s3.bucket');
            
            $folder = Folder::with('files')->where('id' , $request->folder_id)->first();
            
            $bucketAddress = "https://$bucketName.s3.amazonaws.com/".$folder->name;

            $thumbnailPath = public_path('uploads');

            return ["success" => true , "folder" => $folder , 'bucketAddress' => $bucketAddress , 'thumbnailPath' => $thumbnailPath];

        }

        public function clientFolder($request){
            $name = $request->name;

            if(isset($name) && !is_null($name)){
                $folders = Folder::where('name' ,'like' , '%'.$name.'%')->where('client_id' , auth()->user()->id)->get();
                return ['status' => true , 'folders' => $folders];
            }else{
                $folders = Folder::where('client_id' , auth()->user()->id)->get();
                return ['status' => true , 'folders' => $folders];
            }

        }

        public function searchFolder($request){
            $name = $request->name;

            $query = Folder::query();

            $query->when(isset($request->name) && !is_null($request->name) , function($query1) use($name){
                $query1->where('name' , 'like' ,'%'.$name.'%' );
            });

            $folder = $query->where('client_id' , auth()->user()->id)->orderBy('id' , 'desc')->get();

            return ['status' => true , 'folder' => $folder ];


        }




}


