<?php

namespace App\Http\Repository;
use App\Models\{Address, EditorProfile , Skill , EditorPortfolio, Education , User , Favourite };

class EditorHandler{

    public function editorProfile($request)
    {
        $title = $request->title;
        $bio = $request->bio;
        $service = $request->service_offering;

        $skills = json_decode($request->skills);

        $userId = auth()->user()->id;

        EditorProfile::updateOrCreate( 
                [ "editor_id" => $userId],
                [
                    "editor_id" => $userId,
                    "title" => $title,
                    "bio"   => $bio,
                    "service_offering" => $service,
                ]
            );

        $skillable ="App\Models\User";
        $skillList = [];


        foreach($skills as $skill)
        {
            array_push($skillList , ["title" => $skill , "skillable_id" => auth()->user()->id , "skillable_type" => $skillable ]);
        }

        if(sizeof($skillList) > 0)
        {
            Skill::insert($skillList);
        }

        return ["success" => true , "msg" => "Profile Updated Successfully" ];       

    }

    public function updateEditorSkills($request){

        $skills =json_decode($request->skills);
        $skillable ="App\Models\User";
        $skillList = [];

        Skill::where('skillable_type' , $skillable)->where('skillable_id' , auth()->user()->id)->delete();

        foreach($skills as $skill)
        {
            array_push($skillList , ["title" => $skill , "skillable_id" => auth()->user()->id , "skillable_type" => $skillable ]);
        }

        if(sizeof($skillList) > 0)
        {
            Skill::insert($skillList);
        }

        return ["success" => true , "msg" => "Skills Updated" ];      
    }

    public function updateEditorDetail($request)
    {
        $title = $request->title;
        $bio = $request->bio;
        $serviceOffer = $request->service_offering;
        $amountPerHour = $request->amount_per_hour;

        $editorProfile = EditorProfile::where('editor_id' , auth()->user()->id)->first();
        
        if(isset($title) && !is_null($title)){
            $editorProfile->title = $title;
        }

        if(isset($bio) && !is_null($bio)){
            $editorProfile->bio = $bio;
        }

        if(isset($serviceOffer) && !is_null($serviceOffer)){
            $editorProfile->service_offering = $serviceOffer;
        }

        if(isset($amountPerHour) && !is_null($amountPerHour)){
            $editorProfile->amount_per_hour = $amountPerHour;
        }

        $editorProfile->save();

        return ["success" => true , "msg" => "Editor Detail Updated" ]; 

    }


    public function editorPortfolio($request)
    {
        $links = json_decode($request->link);
        
        $portfolio = [];

        foreach($links as $link)
        {
            $portfolio[] = [ "editor_id" => auth()->user()->id , "link" => $link ]; 
        }

        EditorPortfolio::insert($portfolio);

        return ["success"=> true , "msg" => "Editor Portfolio Updated Successfully"];

    }


    public function editorEducation($request)
    {
        $degrees = json_decode($request->education);
          
        $degreeList = [];

        Education::where('user_id' , auth()->user()->id)->delete();
        
        foreach($degrees as $degree)
        {
            $degreeList[] = [ "user_id" => auth()->user()->id , "institution" => $degree->institute , "degree" => $degree->degree , "start_date" => $degree->start_date, "end_date" => $degree->end_date ];
        }

        Education::insert($degreeList);

        return ["success"=> true , "msg" => "Editor Education Updated Successfully"];
    }


    public function editorHourlyRate($request)
    {
        $amount = $request->amount_per_hour;
        $editorId = auth()->user()->id;
        EditorProfile::updateOrCreate(
            ['editor_id' => $editorId],
            [
                "amount_per_hour" => $amount,
                'editor_id' => $editorId,
            ]
            );
        
        return ["success"=> true , "msg" => "Editor Per Hour Rate Updated Successfully"];

    }

    public function updateEditorAddress($request)
    {
        $country = $request->country;
        $city = $request->city;
        $address = $request->address;
        $language = $request->language;

        Address::updateOrCreate(
            [ "user_id" => auth()->user()->id],
            [ 
                "country" => $country,
                "city"   => $city,
                "address" => $address,
                "language"=> $language,
                "user_id" => auth()->user()->id
            ] 
        );

        if($request->hasFile("profile_image"))
        {
            $file = $request->file("profile_image");
            $fileName = time()."-".$file->getClientOriginalName();
            $file->move(public_path("uploads") , $fileName);
            User::where('id',auth()->user()->id)->update(['profile_image' => $fileName]);
        }

        return ["success"=> true , "msg" => "Editor Profile Image Updated Successfully"];


    }

    public function updateEditorBiography($request)
    {
        $biography = $request->biography;
        EditorProfile::updateOrCreate(
            ["editor_id" => auth()->user()->id],
            ["editor_id" => auth()->user()->id , "bio" => $biography]
        );
        return ["success"=> true , "msg" => "Editor Profile Biography Updated Successfully"];
    }

    public function updateEditorLanguage($request)
    {
        $language = $request->language;
        Address::updateOrCreate(
            ["user_id" => auth()->user()->id],
            ["user_id" => auth()->user()->id , "language" => $language]
        );
        return ["success"=> true , "msg" => "Editor Language Updated Successfully"];
    }

    public function deleteEditorSkill($request)
    {
        $skillId = $request->skill_id;
        Skill::where(["id" => $skillId])->delete();
        return ["success"=> true , "msg" => "Editor skill deleted Successfully"];
    }

    public function addMoreEditorSkill($request)
    {
        $skills = json_decode($request->skills);
        $skillList = [];
        $skillable = "App\Models\User";
        foreach($skills as $skill)
        {
            $skillList[] = ["title" => $skill , "skillable_id" => auth()->user()->id ,"skillable_type" => $skillable ];
        }

        Skill::insert($skillList);

        return ["success"=> true , "msg" => "Editor Skill Updated Successfully"];
    }

    public function updateEditorEducation($request)
    {
        $educationId = $request->education_id;
        $institute = $request->institute;
        $degree = $request->degree;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        Education::where("id" , $educationId)->update([ 
            "institution" => $institute,
            "degree"    => $degree,
            "start_date"    => $startDate,
            "end_date"      => $endDate
        ]);

        return ["success"=> true , "msg" => "Editor Education Updated Successfully"];
    }



}