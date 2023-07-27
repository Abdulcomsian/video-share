<?php

namespace App\Http\Repository;
use App\Models\Files;
use File;


class FilesHandler{

    public function uploadMedia($request)
    {
        $folderId = $request->folder_id;
        $fileList = [];
        $videoExtension = ['mp4' , 'webm'];
        // dd("inside file");
        if($request->hasFile('files'))
        {
            foreach($request->file('files') as $file){
                
                $fileName = $file->getClientOriginalName();
                $extension = $file->extension();
                $name = time()."-".$fileName;
                $file->move(public_path('uploads'), $name);
                $type =in_array($extension , $videoExtension) ? 1 : 2;
                $fileList[] = ['folder_id' => $folderId , 'type' => $type , 'path' => $name , 'extension' => $extension];
            }
            
            Files::insert($fileList);    
        }

        return ["success" => true , "msg" => "Files Added Successfully"];
    }

    public function deleteMedia($request)
    {
        $fileId = $request->file_id;

        $file = Files::where('id' , $fileId)->first();
        $fileName = $file->path;
        // dd(public_path('uploads')."/".$fileName);
        if(File::exists(public_path('uploads')."/".$fileName))
        {
            File::delete(public_path('upload')."/".$fileName);
        }

        $file->delete();

        return ["success" => true , "msg" => "File Deleted Successfully"];

    }

}