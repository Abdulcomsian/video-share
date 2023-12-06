<?php

namespace App\Http\Repository;
use App\Models\{Country , City};

class HomeHandler{
    public function termAndConditionText(){
        return 'Terms of Service for OpenEdit

        Effective Date: 01/01/2024
        
        These Terms of Service ("Terms") govern your use of the OpenEdit (the "Application") provided by [OpenEdit.com], a [Legal Structure] ("we," "us," or "our"). By accessing or using the Application, you agree to comply with and be bound by these Terms. If you do not agree with these Terms, please refrain from using the Application.
        
        1. Acceptance of Terms
        By accessing or using the Application, you acknowledge that you have read, understood, and agree to be bound by these Terms, as well as any additional terms and conditions provided by us.
        
        2. User Eligibility
        You must be at least 18 years old to use the Application. By using the Application, you represent and warrant that you are at least 18 years old and have the legal capacity to enter into these Terms.
        
        3. User Accounts
        3.1 Registration: To access certain features of the Application, you may be required to register for an account. You agree to provide accurate, current, and complete information during the registration process.
        
        3.2 Account Security: You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. Notify us immediately if you become aware of any unauthorized use of your account.
        
        4. Freelancer Services
        4.1 Video Editing Services: Users offering video editing services ("Freelancers") are responsible for the quality and timely delivery of their services.
        
        4.2 Payment: Payments for Freelancer services will be handled through [Payment Processor]. We are not responsible for any issues related to payment transactions.
        
        5. Intellectual Property
        5.1 User Content: By uploading or submitting content to the Application, you grant us a non-exclusive, worldwide, royalty-free license to use, reproduce, modify, and distribute the content.
        
        5.2 Copyright Infringement: We respect intellectual property rights. If you believe that any content on the Application infringes your copyright, please contact us.
        
        6. Prohibited Conduct
        You agree not to engage in any conduct that violates these Terms, including but not limited to:
        
        6.1 Illegal Activities: Engaging in any illegal activities through the use of the Application.
        
        6.2 Abuse: Harassing, intimidating, or abusing other users.
        
        7. Termination
        We reserve the right to suspend or terminate your access to the Application at our discretion, without prior notice, for any violation of these Terms.
        
        8. Disclaimer of Warranties
        The Application is provided "as is" without any warranties. We do not guarantee the accuracy, completeness, or reliability of any content or information on the Application.
        
        9. Limitation of Liability
        To the extent permitted by law, we shall not be liable for any indirect, consequential, or incidental damages arising out of or in connection with your use of the Application.
        
        10. Changes to Terms
        We reserve the right to modify or update these Terms at any time. We will notify users of any significant changes.
        
        11. Governing Law
        These Terms are governed by the laws of [Your Jurisdiction].
        
        By using the Application, you agree to these Terms. If you have any questions or concerns, please contact us at OpenEdit.dev.com.';
    }


    public function getCountriesList(){
        $countriesList = Country::get();
        return $countriesList;
    }

    public function getCitiesList($request){
        $citiesList = City::where('country_id' , $request->country_id)->get();
        return $citiesList;
    }
}