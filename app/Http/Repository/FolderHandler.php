<?php


namespace App\Http\Repository;

use App\Models\Folder;
use App\Models\Files;
use Illuminate\Support\Facades\Storage;

class FolderHandler
{

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

                    $check = Storage::disk('s3')->makeDirectory($folderName);

                    if($check)
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

            $folders = Folder::where('client_id' , $userId)->get();

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

            $folder = Folder::where('id' , $folderId)->first();
            
            Storage::disk('s3')->deleteDirectory($folder->name);
            
            $folder->delete();

            return ["success" => true , "msg" => "Folder Deleted Successfully"];
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
                $files = Storage::disk('s3')->allFiles($folder->name);

                $newFolder = Storage::disk('s3')->makeDirectory($folderName);

                if($newFolder){
                    
                    foreach($files as $file)
                    {
                        $newKey = str_replace($folder->name, $folderName, $file);
                        
                        Storage::disk('s3')->copy($file, $newKey);
                    }

                    Storage::disk('s3')->deleteDirectory($folder->name);

                    $folder->name = $folderName;

                    $folder->save();

                    return ["success" => true , "msg" => "Folder Updated Successfully"];
                }else{

                    return ["success" => false , "msg" => "Something Went Wrong"];
                }


                
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

            $bucketAddress = "https://$bucketName.s3.amazonaws.com/";

            $folder = Folder::find($folderId);

            $folderPath = $folder->name;

            $filesList = Storage::disk('s3')->files($folderPath);

            $files = array_map( function($file) use ($bucketAddress){
                return $bucketAddress.$file;
            }, $filesList);

            return ["success" => true , "files" => $files ];
        }


}


