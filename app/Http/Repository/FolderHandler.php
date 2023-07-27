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

                Folder::create([
                    "client_id" => $clientId,
                    "name"  => $name
                ]);

                return ["success" => true , "msg" => "Folder Created Successfully"];

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

            Folder::where('id' , $folderId)->delete();
            
            return ["success" => true , "msg" => "Folder Deleted Successfully"];
        }

        public function updateFolder($request)
        {
            $folderId = $request->folder_id;
            $name = $request->folder_name;

            Folder::where('id' , $folderId)->update(['name' => $name]);

            return ["success" => true , "msg" => "Folder Updated Successfully"];

        }
}


