<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\FolderHandler;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Yajra\DataTables\Contracts\DataTable;

class FolderController extends Controller
{
    protected $folderHandler;

    public function __construct(FolderHandler $folderHandler)
    {
        $this->folderHandler = $folderHandler;
    }

    public function addFolderFile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'folder_id' => 'required',
                'files.*' => 'file|mimes:mp4,avi,jpg,png,jpeg,mpeg|max:51200' // Maximum file size of 50MB (50 * 1024 = 51200 kilobytes)
            ]);

            if ($validator->fails()) {

                return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $validator->getMessageBag()] ,400);
            
            } else {
                
                $response = $this->folderHandler->createFilesInFolder($request);
                
                return response()->json($response);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }

    public function createClientFolder(Request $request){

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $validator->getMessageBag()] ,400);
            
            } else {
                
                $response = $this->folderHandler->createFolder($request);
                
                return response()->json($response);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }

    }

    public function getClientFolders()
    {
        try{

            $response = $this->folderHandler->clientFolders();
                
            return response()->json($response); 

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        
        }
    }

    public function getFolderDetail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'folder_id' => 'required',
            ]);

            if ($validator->fails()) {

                return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $validator->getMessageBag()] ,400);
            
            } else {
                
                $response = $this->folderHandler->folderDetail($request);
                
                return response()->json($response);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }


    public function deleteClientFolder(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'folder_id' => 'required',
            ]);   
            
            if ($validator->fails()) {

                return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $validator->getMessageBag()] ,400);
            
            } else {
                
                $response = $this->folderHandler->deleteFolder($request);
                
                return response()->json($response);
            }

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }

    public function updateClientFolder(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'folder_id' => 'required',
                'folder_name' => 'required'
            ]);   
            
            if ($validator->fails()) {

                return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $validator->getMessageBag()] ,400);
            
            } else {
                
                $response = $this->folderHandler->updateFolder($request);
                
                return response()->json($response);
            }

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }

    public function getFolderList()
    {
        try {

           $folders =  $this->folderHandler->folderList();

           $folderList = $folders['folderList'];

           return DataTables::of($folderList)
                        ->addIndexColumn()
                        ->addColumn("client_name" , function($folder){
                            return $folder->client->full_name;
                        })
                        ->addColumn("client_email" , function($folder){
                            return $folder->client->email;
                        })
                        ->addColumn("folder_name" , function($folder){
                            return $folder->name;
                        })
                        ->addColumn("action" , function($folder){
                            return "<div><a href=".url('folder/'.$folder->id)."></a> <i class='fas fa-eye'></i></div>";
                        })
                        ->rawColumns(['action'])
                        ->make(true);

        } catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }


    public function getFolderFiles(Request $request)
    {
        $validator = Validator::make( $request->all() , [
            'folder_id' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['success' => false , 'error' => $validator->getMessageBag()]); 
        }else{

            $response = $this->folderHandler->getFiles($request);

            return response()->json($response);

        }

    }


}
