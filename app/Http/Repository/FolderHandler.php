<?php

namespace App\Http\Repository;
use App\Models\Folder;

class FolderHandler{

    public function createClientFolder($request)
    {
        $clientId = auth()->user()->id;
        $name  = $request->name;

        Folder::create([
            "client_id" => $clientId,
            "name"  => $name
        ]);

        return ["success" => true , "msg" => "Folder Created Successfully"];

    }

}