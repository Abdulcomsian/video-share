<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Repository\{ EditorHandler , UserHandler };
use App\Models\EditorPortfolio;
use DataTables;
use PhpParser\Node\Expr\AssignOp\Div;

class UserController extends Controller
{
    protected $editorHandler;
    protected $userHandler;

    public function __construct(EditorHandler $editorHandler , UserHandler $userHandler)
    {
        $this->editorHandler = $editorHandler;    
        $this->userHandler  = $userHandler;
    }

    public function updateEditorProfile(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "title" => "required|string",
                "bio" => "required|string",
                "service_offering" => "required",
            ]);


            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
                
            }else{
                $response = $this->editorHandler->editorProfile($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }


    public function updateEditorPortfolio(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "links" => "required|string",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                return $this->editorHandler->editorPortfolio($request);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateProfile(Request $request)
    {
        try{

            if(isset($request->links)){
                $this->editorHandler->editorPortfolio($request);
            }

            if($request->title || $request->bio || $request->service_offering || $request->amount_per_hour || $request->language){
                $this->editorHandler->updateEditorDetail($request);
            }

            if($request->skills){
                $this->editorHandler->updateEditorSkills($request);
            }
           

            if($request->language){
                $this->editorHandler->updateEditorLanguage($request);
            }
          
            if($request->education){
                $this->editorHandler->editorEducation($request);
            }


            return response()->json(['success' => true , 'msg' => 'Profile Updated Successfully']);


        }catch(\Exception $e){

            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        
        }
    }

    public function updateEditorEducation(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "education" => "required|string",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{
                $response = $this->editorHandler->editorEducation($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateHourlyRate(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "amount_per_hour" => "required|numeric",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->editorHourlyRate($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }


    public function updateAddress(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "country" => "required|numeric",
                "address" => "required|string",
                "city"    => "required|numeric",
                "language"=> "required|string"
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->updateEditorAddress($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateBiography(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "biography" => "required|string",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->updateEditorBiography($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateLanguage(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "language" => "required|string",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->updateEditorLanguage($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function deleteSkill(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "skill_id" => "required|numeric",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->deleteEditorSkill($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function addEditorSkill(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "skills" => "required|string",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->addMoreEditorSkill($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateEducation(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "institute"   => "required|string",
                "degree"        => "required|string",
                "start_date"    => "required|string",
                "end_date"      => "required|string"
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->updateEditorEducation($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function addFavourite(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "editor_id"   => "required|numeric",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->userHandler->addEditorFavourite($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        } 
    }


    public function getFavouriteList()
    {
        try{

            $response = $this->userHandler->favouriteList();

            return response()->json($response);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        } 
    }

    public function getProfileDetail(Request $request)
    {
        $validator = Validator::make($request->all() , [
                        'editor_id' => 'nullable|numeric'
                    ]);

        if($validator->fails()){
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $validator->getMessage()] ,400); 
        }

        try{
            $editorId = $request->editor_id ?? auth()->user()->id;

            $response = $this->userHandler->profileDetail($editorId);

            return response()->json($response);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        } 
    }

    public function getEditorList()
    {
        try{

            $response = $this->userHandler->editorList();

            return response()->json($response);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateProfileImage(Request $request){
        try{
            // $validator = Validator::make( $request->all() , [
            //  'file' => 'required|max:10000|mimes:jfif,jpef,jpg,png'
            // ]);
     
            // if($validator->fails()){
            //      return response()->json(['success' => false , 'msg' => "Something Went Wrong" , "error" => $validator->getMessageBag()] , 400);
            // }else{
                $response = $this->userHandler->updateProfileImage($request);

                return response()->json($response);
            // }
        }catch(\Exception $e){
            return response()->json(['success' => false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] , 400);
        }
    }



    public function getDashboardClientList()
    {
        try{
            $response  = $this->userHandler->clientList();

            $clientList = $response['clientList'];

           return \DataTables::of($clientList)
                    ->addIndexColumn()
                    ->addColumn('name' , function($client){
                        return $client->full_name;
                    })
                    ->addColumn('image' , function($client){
                        $file = is_null($client->image) ? asset('uploads/avatar.png') : asset('uploads/'.$client->image) ;
                        return "<img src='$file'/>";
                    })
                    ->addColumn('email' , function($client){
                        return $client->email;
                    })
                    ->addColumn('phone' , function($client){
                        return $client->phone_number ?? "<div class='px-5'>--</div>";
                    })
                    ->rawColumns(['image' , 'phone'])
                    ->make(true);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function getDashboardEditorList()
    {
        try{
            $response  = $this->userHandler->editorList();

            $editorList = $response['editorList'];

           return \DataTables::of($editorList)
                    ->addIndexColumn()
                    ->addColumn('name' , function($editor){
                        return $editor->full_name;
                    })
                    ->addColumn('image' , function($editor){
                        $file = is_null($editor->image) ? asset('uploads/avatar.png')  : asset('uploads/'.$editor->image);
                        return "<img src='$file'/>";
                    })
                    ->addColumn('email' , function($editor){
                        return $editor->email;
                    })
                    ->addColumn('phone' , function($editor){
                        return $editor->phone_number ?? "<div class='px-5'>--</div>";
                    })
                    ->rawColumns(['image' , 'phone'])
                    ->make(true);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateLinkPage()
    {
        return view('update-link');
    }

    public function addLinks(Request $request){

        $fileName = [];
        $files = $request->input("files");
       
        foreach($files as $index => $file){
            if($file == "null")
            {
                $fileName[] = null;
            }else{
                $imageUrl = substr($file, strpos($file, ',') + 1);
                $image = base64_decode($imageUrl);
                $imageName = time().$index."-portfolio-thumbnail".".png";
                file_put_contents(public_path()."/test/".$imageName , $image);
                $fileName[] = $imageName;
            }
        }

        EditorPortfolio::where('editor_id', 2)->delete();
        $links = [];
        foreach($request->links as $index => $link){
            $links[] = ["editor_id" => 2 , "link" => $link , "thumbnail" => $fileName[$index]];
        }

        EditorPortfolio::insert($links);

        return response()->json(["success" => true , "msg" => "Editor Portfolio Added Successfully"]);

    }
    
    
    public function changePassword(Request $request){

        $validator = Validator::make($request->all() , [
            'old_password' => 'required|string',
            'new_password' => 'required|string',
            'confirm_password' => 'required|same:new_password'
        ]);

        if($validator->fails()){
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $validator->getMessageBag()] ,400);
        }


        try{

            $response = $this->userHandler->changePassword($request);

            if(!$response['status']){
                return response()->json( $response , 400);
            }

            return response()->json($response);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        } 
    }

    public function forgetPassword(Request $request){
        $validator = Validator::make($request->all() , [
            'email' => 'required|email',
        ]);

        if($validator->fails()){
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $validator->getMessageBag()] ,400);
        }

        try{

            $response = $this->userHandler->forgetPassword($request);

            if(!$response['status']){
                return response()->json( $response , 400);
            }

            return response()->json($response);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        } 
    }

    public function updatePassword(Request $request){
        $validator = Validator::make($request->all() , [
            'email' => 'required|email',
            'verification_code' => 'required|string',
            'new_password' => 'required|string',
            'confirm_password' => 'required|same:new_password'
        ]);

        if($validator->fails()){
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $validator->getMessageBag()] ,400);
        }

        try{

            $response = $this->userHandler->updatePassword($request);

            if(!$response['status']){
                return response()->json( $response , 400);
            }

            return response()->json($response);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        } 
    }


    public function sendPasscode(Request $request){
        $validator = Validator::make($request->all() , [
            'email' => 'required|email',
        ]);

        if($validator->fails()){
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $validator->getMessageBag()] ,400);
        }

        try{

            $response = $this->userHandler->sendVerificationCode($request);

            if(!$response['status']){
                return response()->json( $response , 400);
            }

            return response()->json($response);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        } 
    }


    public function setPushNotification(Request $request){
        $validator = Validator ::make($request->all() , [
            "status" => "required|boolean",
        ]);

        if($validator->fails()){
            return response()->json(["status" => false , "msg" => "Something Went Wrong" , "error" => $validator->getMessageBag()]);
        }

        try{
            
            $response = $this->userHandler->updatePushNotification($request);

            return response()->json($response);

        }catch(\Exception $e){

            return response()->json(["status" => false , "msg" => "Something Went Wrong" , "error" => $e->getMessage()]);  
        }
    }

    public function deleteUser(){
        try{
            
            $response = $this->userHandler->deleteUserProfile();

            return response()->json($response);

        }catch(\Exception $e){

            return response()->json(["status" => false , "msg" => "Something Went Wrong" , "error" => $e->getMessage()]);  
        }  
    }

    public function getClientProfile(){
        try{
            
            $response = $this->userHandler->clientProfile();

            return response()->json($response);

        }catch(\Exception $e){

            return response()->json(["status" => false , "msg" => "Something Went Wrong" , "error" => $e->getMessage()] , 400);  
        }  
    }

   

   public function updateUserProfile(Request $request){
    $validator = Validator ::make($request->all() , [
        "full_name" => "required|string",
        "country_id" => "required|numeric",
        "city_id" => "required|numeric",
        "language" => "required|string",
        "address" => "required|string"
    ]);

    if($validator->fails()){
        return response()->json(["status" => false , "msg" => "Something Went Wrong" , "error" => $validator->getMessageBag()]);
    }

    try{
        
        $response = $this->userHandler->updateUserProfile($request);

        return response()->json($response);

    }catch(\Exception $e){

        return response()->json(["status" => false , "msg" => "Something Went Wrong" , "error" => $e->getMessage()]);  
    }

   }

}
