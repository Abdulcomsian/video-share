<?php

namespace App\Http\Controllers;

use App\Http\AppConst;
use Illuminate\Http\Request;
use App\Http\Repository\HomeHandler;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    protected $homeHandler;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HomeHandler $home)
    {
        $this->homeHandler = $home;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function getTermsAndCondition(){
        $text =  $this->homeHandler->termAndConditionText();
        return response()->json(["status"  => true , "text" => $text ]);
    }

    public function getCountries(){
        $countryList = $this->homeHandler->getCountriesList();
        return response()->json(["status" => true , "countryList" => $countryList]);
    }

    public function getCities(Request $request){
        $validator = Validator::make($request->all() , [
            'country_id' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(["status" => false , "msg" => "Something Went Wrong" , "error" => $validator->getMessageBag()]);
        }

        $citiesList = $this->homeHandler->getCitiesList($request);

        return response()->json(["status" => true , "citiesList" => $citiesList]);
    }


    public function getFrequentlyAskQuestion(){
        $clientQuestion = [
            "How do I create an account on the platform?",
            "How can I post a video editing project on the platform?",
            "What information should I include in my project description?",
            "How do I choose an editor for my project?",
            "What if I'm not satisfied with the edited video?",
            "How does payment work on the platform?",
            "Can I request revisions after the project is completed?",
            "What if there's a dispute between me and the editor?",
            "How do I leave feedback for an editor?",
            "Is there a way to communicate with the editor during the project?",
        ];

        $editorQuestion = [
            "How do I create an editor profile on the platform?",
            "How can I find and apply for video editing projects?",
            "What should I include in my proposal to clients?",
            "How does payment work for editors on the platform?",
            "Can I negotiate project terms with the client?",
            "What if a client is not satisfied with my work?",
            "Is there a limit to the number of projects I can take on at once?",
            "How do I communicate with clients during a project?",
            "Can I showcase my previous work on my profile?",
            "What should I do if a dispute arises with a client?"
        ];

        return response()->json(["status" => true , 'questions' => auth()->user()->type == AppConst::CLIENT ? $clientQuestion : $editorQuestion]);
    }

    public function termsAndConditionPage(){
        return view('terms-and-condition');
    }

}
