<?php

// namespace App\Http\Repository;
// use App\Models\Folder;

// class FolderHandler{

//     public function createClientFolder($request)
//     {
//         $clientId = auth()->user()->id;
//         $name  = $request->name;

//         Folder::create([
//             "client_id" => $clientId,
//             "name"  => $name
//         ]);

//         return ["success" => true , "msg" => "Folder Created Successfully"];

//     }

// }


// FolderController.php

namespace App\Http\Repository;

use App\Models\Folder;
use App\Models\Files;
use Illuminate\Support\Facades\Storage;

class FolderHandler
{
    // public function createFilesInFolder($request)
    // {
    //     $folderId = $request->folder_id;
    //     $files = $request->file('files');
    //     $folder = Folder::findOrFail($folderId);

    //     // if ($files) {
    //         // foreach ($files as $file) {
    //             // Store each file in the folder
    //             $path = Storage::putFile('folder_files/' . $folder->id, $files);
    //             $extension = $files->getClientOriginalExtension();
    //             // Create a new file record in the database
    //             $createdFile = $folder->files()->create([
    //                 'folder_id' => 1,
    //                 'path' => 'ABC',
    //                 'extension' => 'png',
    //                 'type' => '1',


    //             ]);

    //             // $createdFile->refresh(); // Refresh the file instance to get the updated data
    //     //     }
    //     // }

    //     return [
    //         "success" => true,
    //         "msg" => "Files Uploaded Successfully",
    //         "folder_id" => $folder->id,
    //         "files" => $folder->files->map(function ($file)
    //         {
    //             return [
    //                 "id" => $file->id,
    //                 "path" => $file->path,
    //                 "extension" => $file->extension,
    //                 "type" => $file->type,
    //                 "folder_id" => $file->folder_id,
    //             ];
    //         }),
    //     ];
    // }

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

}


