<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\HomeHandler;
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


    public function getFrequentlyAskQuestion(){
        $question = [
            "How does the payment process work for freelancers?",
            "Can I preview a freelancer's previous work before hiring them?",
            "What measures are in place to ensure the security of my project files and content?",
            "How do I communicate with the freelancer during a project?",
            "What happens if there's a dispute between a client and a freelancer?"
        ];

        return response()->json(["status" => true , 'questions' => $question]);
    }

    public function termsAndConditionPage(){
        return view('terms-and-condition');
    }

}
