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
            [
            "question" => "How do I create an account on the platform?" , 
            "answer" =>  "To create an account, click on the 'Sign Up' button on the homepage, fill in the required information, and follow the on-screen instructions."
            ],
            [
            "question" => "How can I post a video editing project on the platform?" , 
            "answer" =>  "After logging in, go to your dashboard and click on 'Post a Project.' Fill in the project details, including a brief description, budget, and any specific requirements. Click 'Submit' to post your project for editors to see.",
            ],
            [
            "question" => "What information should I include in my project description?" , 
            "answer" =>  "Include details about your project's goals, style preferences, desired video length, and any specific instructions. The more information you provide, the better editors can understand and meet your needs.",
            ],
            [
            "question" => "How do I choose an editor for my project?" , 
            "answer" =>  "Review editor profiles, portfolios, and ratings. You can also communicate with editors to discuss your project before making a decision. Once you find a suitable match, click 'Hire' on their profile to start the collaboration.",
            ],
            [
            "question" => "What if I'm not satisfied with the edited video?" , 
            "answer" =>  "You can request revisions from the editor. If issues persist, our support team is here to help mediate and ensure your satisfaction. Your payment is held in escrow until the project meets your expectations.",
            ],
            [
            "question" => "How does payment work on the platform?" , "answer" =>  
            "Payments are securely processed through the platform. When you hire an editor, the agreed-upon amount is held in escrow. Once you approve the final video, the payment is released to the editor.",
            ],
            [
            "question" => "Can I request revisions after the project is completed?" , 
            "answer" =>  "Yes, you can request revisions until you are satisfied with the edited video. Communication with the editor is key during this phase to ensure your vision is met.",
            ],
            [
            "question" => "What if there's a dispute between me and the editor?" , 
            "answer" =>  "If a dispute arises, contact our support team immediately. We will work with both parties to resolve the issue and ensure a fair outcome.",
            ],
            [
            "question" => "How do I leave feedback for an editor?" , "answer" =>  
            "After the project is completed, you can leave feedback and rate the editor on their profile. Your feedback helps build a trusted community and assists other clients in choosing the right editors.",
            ],
            [
            "question" => "Is there a way to communicate with the editor during the project?" , "answer" =>  
            "Yes, you can communicate with the editor through our messaging system. Discuss project details, provide feedback, and ask questions to ensure a smooth collaboration.",
            ]
        ];

        $editorQuestion = [
            [
                "question" => "How do I create an editor profile on the platform?",
                "answer" => "To create an editor profile, log in and navigate to your profile settings. Fill in the required information, including your skills, experience, and portfolio. Click 'Save' to publish your profile for clients to view.",
            ],
            [
                "question" => "How can I find and apply for video editing projects?",
                "answer" => "Browse available projects on your dashboard. Filter by category, budget, and other preferences. Click on a project to view details and submit a compelling proposal outlining your skills and approach to the project.",
            ],
            [
                "question" => "What should I include in my proposal to clients?",
                "answer" => "In your proposal, introduce yourself, highlight relevant experience, and outline your approach to the project. Mention why you're the right fit and address any specific requirements the client has mentioned in their project description.",
            ],
            [
                "question" => "How does payment work for editors on the platform?",
                "answer" => "Payments are securely processed through the platform. Once a client hires you, the agreed-upon amount is held in escrow. Upon project completion and client approval, the payment is released to your account.",
            ],
            [
                "question" => "Can I negotiate project terms with the client?",
                "answer" => "Yes, you can negotiate project terms with the client. Use the platform's messaging system to discuss details such as pricing, timeline, and any specific project requirements before accepting an offer.",
            ],
            [
                "question" => "What if a client is not satisfied with my work?",
                "answer" => "If a client is not satisfied, they can request revisions. It's important to communicate openly, address their feedback, and make necessary adjustments. Your payment is held in escrow until the client approves the final work.",
            ],
            [
                "question" => "Is there a limit to the number of projects I can take on at once?",
                "answer" => "There is no strict limit, but it's recommended to take on a manageable workload to ensure the quality of your work. Prioritize effective communication and meeting deadlines for a positive client experience.",
            ],
            [
                "question" => "How do I communicate with clients during a project?",
                "answer" => "Use the platform's messaging system to communicate with clients. Discuss project details, provide updates, and address any questions or concerns the client may have. Clear communication is key to a successful collaboration.",
            ],
            [
                "question" => "Can I showcase my previous work on my profile?",
                "answer" => "Yes, you can showcase your previous work in your profile. Upload samples of your best projects, add descriptions, and highlight your skills and style. A strong portfolio increases your chances of attracting clients.",
            ],
            [
                "question" => "What should I do if a dispute arises with a client?",
                "answer" => "In case of a dispute, contact the platform's support team immediately. They will assist in resolving the issue and ensuring a fair outcome for both you and the client.",
            ],
        ];


        return response()->json(["status" => true , 'questions' => auth()->user()->type == AppConst::CLIENT ? $clientQuestion : $editorQuestion]);
    }

    public function termsAndConditionPage(){
        return view('terms-and-condition');
    }

    public function suggestedSkills(){
        $suggestedSkills = $this->homeHandler->getSuggestedSkills();
        return response()->json(["status" => true , "suggestedSkills" => $suggestedSkills]);
    }

}
