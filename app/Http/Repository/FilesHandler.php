<?php

namespace App\Http\Repository;
use File;
use Illuminate\Support\Facades\Storage;
use App\Models\{ Files , Folder, PersonalJob , ShareFolderFiles};

class FilesHandler{

    public function uploadMedia($request)
    {
        try{
            $folderId = $request->folder_id;
            $fileList = [];
            $videoExtension = ['mp4' , 'webm'];
            // dd("inside file");

            $folder = Folder::find($folderId);

            if($request->hasFile('files'))
            {
                foreach($request->file('files') as $file){

                    $fileName = $file->getClientOriginalName();
                    $extension = $file->extension();
                    $name = time() . "-" . $fileName;
                    Storage::disk('s3')->put($folder->name.'/'.$name, file_get_contents($file));     

                    // $fileName = $file->getClientOriginalName();
                    // $extension = $file->extension();
                    // $name = time()."-".$fileName;
                    // $file->move(public_path('uploads'), $name);
                    $type =in_array($extension , $videoExtension) ? 1 : 2;
                    $fileList[] = ['folder_id' => $folderId , 'type' => $type , 'path' => $name , 'extension' => $extension];
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
            $folder = Folder::find($file->folder_id);
            $fileName = $folder->name.'/'.$file->path;
    
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


    public function uploadShareFolderFiles($request)
    {
        try{
            $jobId = $request->job_id;
            $personalJob = PersonalJob::with('folder')->where('id' , $jobId)->first();
            $folderId = $personalJob->folder->id;
            $fileList = [];
            $videoExtension = ['mp4' , 'webm'];
            // dd("inside file");

            $folder = $personalJob->folder;

            if($request->hasFile('files'))
            {
                foreach($request->file('files') as $file){

                    $fileName = $file->getClientOriginalName();
                    $extension = $file->extension();
                    $name = time() . "-" . $fileName;
                    Storage::disk('s3')->put($folder->name.'/'.$name, file_get_contents($file));     

                    // $fileName = $file->getClientOriginalName();
                    // $extension = $file->extension();
                    // $name = time()."-".$fileName;
                    // $file->move(public_path('uploads'), $name);
                    $type =in_array($extension , $videoExtension) ? 1 : 2;
                    $fileList[] = ['share_folder_id' => $folderId , 'type' => $type , 'path' => $name , 'extension' => $extension];
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

}