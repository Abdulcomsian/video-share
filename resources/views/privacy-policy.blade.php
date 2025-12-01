<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - {{ config('variables.systemName') }}</title>
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/bootstrap.min.css')}}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 1rem;
        }
        .logo-container img {
            max-height: 60px;
            width: auto;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .content-card {
            background: white;
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        h1 {
            color: #667eea;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        h2 {
            color: #764ba2;
            font-size: 1.8rem;
            margin-top: 2rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
        }
        h3 {
            color: #495057;
            font-size: 1.3rem;
            margin-top: 1.5rem;
            margin-bottom: 0.8rem;
        }
        p {
            margin-bottom: 1rem;
            text-align: justify;
        }
        ul, ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }
        li {
            margin-bottom: 0.5rem;
        }
        .footer-links {
            text-align: center;
            padding: 2rem 0;
            background: white;
            border-top: 1px solid #e9ecef;
        }
        .footer-links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 1rem;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
        .effective-date {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo-container">
                <img src="{{ asset('images/logo/logo-1.png') }}" alt="{{ config('variables.systemName') }} Logo">
            </div>
            <h1 style="color: white; margin-top: 1rem;">Privacy Policy</h1>
        </div>
    </div>

    <div class="container">
        <div class="content-card">
            {{-- <p class="effective-date"><strong>Effective Date:</strong> {{ date('F d, Y') }}</p> --}}

            <p>At {{ config('variables.systemName') }} ("Platform," "we," "us," or "our"), we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform that connects clients with professional editors for video, image, and media editing services.</p>

            <p>Please read this Privacy Policy carefully. By using our Platform, you agree to the collection and use of information in accordance with this policy. If you do not agree with our policies and practices, please do not use our Platform.</p>

            <h2>1. Information We Collect</h2>
            <h3>1.1 Information You Provide to Us</h3>
            <p>We collect information that you provide directly to us, including:</p>
            <ul>
                <li><strong>Account Information:</strong> Name, email address, password, phone number, and profile information when you create an account.</li>
                <li><strong>Profile Information:</strong> For editors, this may include skills, experience, education, portfolio, hourly rates, biography, and location. For clients, this may include company information and preferences.</li>
                <li><strong>Job Information:</strong> Job descriptions, requirements, budgets, deadlines, and attachments you upload when posting jobs.</li>
                <li><strong>Proposal Information:</strong> Proposals, bids, and communications between clients and editors.</li>
                <li><strong>Payment Information:</strong> Billing address, payment method details (processed securely through Stripe), and transaction history.</li>
                <li><strong>Communication Data:</strong> Messages, comments, reviews, and other communications sent through the Platform.</li>
                <li><strong>Content:</strong> Files, videos, images, and other media you upload to the Platform.</li>
            </ul>

            <h3>1.2 Information We Collect Automatically</h3>
            <p>When you use our Platform, we automatically collect certain information, including:</p>
            <ul>
                <li><strong>Device Information:</strong> IP address, browser type, operating system, device identifiers, and mobile network information.</li>
                <li><strong>Usage Information:</strong> Pages visited, features used, time spent on pages, search queries, and interaction with content.</li>
                <li><strong>Location Information:</strong> General location data based on IP address or device settings (with your permission).</li>
                <li><strong>Cookies and Tracking Technologies:</strong> We use cookies, web beacons, and similar technologies to track activity and store preferences.</li>
            </ul>

            <h3>1.3 Information from Third Parties</h3>
            <p>We may receive information about you from third parties, including:</p>
            <ul>
                <li><strong>Payment Processors:</strong> Stripe provides us with payment transaction information.</li>
                <li><strong>Social Media Platforms:</strong> If you connect your account to social media, we may receive profile information.</li>
                <li><strong>Service Providers:</strong> Third-party services that help us operate the Platform.</li>
            </ul>

            <h2>2. How We Use Your Information</h2>
            <p>We use the information we collect for various purposes, including:</p>
            <ul>
                <li><strong>Platform Operation:</strong> To provide, maintain, and improve our Platform services, including facilitating connections between clients and editors.</li>
                <li><strong>Account Management:</strong> To create and manage your account, authenticate users, and process transactions.</li>
                <li><strong>Job Matching:</strong> To match clients with suitable editors based on skills, experience, and job requirements.</li>
                <li><strong>Communication:</strong> To enable messaging between clients and editors, send notifications, and provide customer support.</li>
                <li><strong>Payment Processing:</strong> To process payments, manage escrow accounts, and handle refunds through Stripe.</li>
                <li><strong>Quality Assurance:</strong> To monitor job completion, resolve disputes, and ensure Platform quality.</li>
                <li><strong>Security:</strong> To detect, prevent, and address fraud, security issues, and unauthorized access.</li>
                <li><strong>Legal Compliance:</strong> To comply with legal obligations, respond to legal requests, and enforce our Terms and Conditions.</li>
                <li><strong>Analytics:</strong> To analyze usage patterns, improve user experience, and develop new features.</li>
                <li><strong>Marketing:</strong> To send promotional communications (with your consent) about Platform features, updates, and relevant services.</li>
            </ul>

            <h2>3. How We Share Your Information</h2>
            <p>We may share your information in the following circumstances:</p>
            <ul>
                <li><strong>With Other Users:</strong> Your profile information, job posts, proposals, and public communications are visible to other Platform users as necessary for the Platform to function.</li>
                <li><strong>With Service Providers:</strong> We share information with third-party service providers who perform services on our behalf, such as payment processing (Stripe), cloud storage, analytics, and customer support.</li>
                <li><strong>For Legal Reasons:</strong> We may disclose information if required by law, court order, or government regulation, or to protect our rights, property, or safety, or that of our users.</li>
                <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets, your information may be transferred to the acquiring entity.</li>
                <li><strong>With Your Consent:</strong> We may share information with your explicit consent or at your direction.</li>
            </ul>
            <p>We do not sell your personal information to third parties for their marketing purposes.</p>

            <h2>4. Data Security</h2>
            <p>We implement appropriate technical and organizational security measures to protect your information against unauthorized access, alteration, disclosure, or destruction. These measures include:</p>
            <ul>
                <li>Encryption of data in transit and at rest;</li>
                <li>Secure authentication and access controls;</li>
                <li>Regular security assessments and updates;</li>
                <li>Secure payment processing through Stripe;</li>
                <li>Limited access to personal information on a need-to-know basis.</li>
            </ul>
            <p>However, no method of transmission over the Internet or electronic storage is 100% secure. While we strive to protect your information, we cannot guarantee absolute security.</p>

            <h2>5. Your Privacy Rights</h2>
            <p>Depending on your location, you may have certain rights regarding your personal information, including:</p>
            <ul>
                <li><strong>Access:</strong> Request access to the personal information we hold about you.</li>
                <li><strong>Correction:</strong> Request correction of inaccurate or incomplete information.</li>
                <li><strong>Deletion:</strong> Request deletion of your personal information (subject to legal and contractual obligations).</li>
                <li><strong>Portability:</strong> Request a copy of your data in a portable format.</li>
                <li><strong>Objection:</strong> Object to certain processing of your information.</li>
                <li><strong>Restriction:</strong> Request restriction of processing in certain circumstances.</li>
                <li><strong>Withdraw Consent:</strong> Withdraw consent where processing is based on consent.</li>
            </ul>
            <p>To exercise these rights, please contact us through the Platform's support system. We will respond to your request within a reasonable timeframe.</p>

            <h2>6. Cookies and Tracking Technologies</h2>
            <p>We use cookies and similar tracking technologies to:</p>
            <ul>
                <li>Remember your preferences and settings;</li>
                <li>Analyze Platform usage and performance;</li>
                <li>Provide personalized content and advertisements;</li>
                <li>Ensure Platform security and prevent fraud.</li>
            </ul>
            <p>You can control cookies through your browser settings. However, disabling cookies may limit your ability to use certain Platform features.</p>

            <h2>7. Third-Party Services</h2>
            <p>Our Platform integrates with third-party services, including:</p>
            <ul>
                <li><strong>Stripe:</strong> For payment processing. Stripe's privacy policy governs their collection and use of payment information.</li>
                <li><strong>Cloud Storage Providers:</strong> For storing and delivering files and media.</li>
                <li><strong>Analytics Services:</strong> For understanding Platform usage and improving services.</li>
            </ul>
            <p>These third-party services have their own privacy policies. We encourage you to review their policies to understand how they handle your information.</p>

            <h2>8. Data Retention</h2>
            <p>We retain your information for as long as necessary to:</p>
            <ul>
                <li>Provide our Platform services;</li>
                <li>Comply with legal obligations;</li>
                <li>Resolve disputes and enforce agreements;</li>
                <li>Maintain business records as required by law.</li>
            </ul>
            <p>When you delete your account, we will delete or anonymize your personal information, except where we are required to retain it for legal or business purposes.</p>

            <h2>9. Children's Privacy</h2>
            <p>Our Platform is not intended for users under the age of 18. We do not knowingly collect personal information from children under 18. If we become aware that we have collected information from a child under 18, we will take steps to delete such information promptly.</p>

            <h2>10. International Data Transfers</h2>
            <p>Your information may be transferred to and processed in countries other than your country of residence. These countries may have different data protection laws. By using our Platform, you consent to the transfer of your information to these countries. We take appropriate measures to ensure your information receives adequate protection.</p>

            <h2>11. Changes to This Privacy Policy</h2>
            <p>We may update this Privacy Policy from time to time. We will notify you of any material changes by:</p>
            <ul>
                <li>Posting the updated Privacy Policy on the Platform;</li>
                <li>Sending an email notification to registered users;</li>
                <li>Updating the "Effective Date" at the top of this Privacy Policy.</li>
            </ul>
            <p>Your continued use of the Platform after changes become effective constitutes acceptance of the updated Privacy Policy.</p>

            <h2>12. Contact Us</h2>
            <p>If you have questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us through the Platform's support system or at the contact information provided on our Platform.</p>

            <h2>13. Your California Privacy Rights</h2>
            <p>If you are a California resident, you have additional rights under the California Consumer Privacy Act (CCPA), including the right to know what personal information we collect, the right to delete personal information, and the right to opt-out of the sale of personal information (we do not sell personal information).</p>

            <h2>14. Your European Privacy Rights</h2>
            <p>If you are located in the European Economic Area (EEA), you have additional rights under the General Data Protection Regulation (GDPR), including the right to access, rectify, erase, restrict processing, data portability, and object to processing of your personal information.</p>
        </div>

        <div class="footer-links">
            <a href="{{ route('get.privacyPolicy.page') }}">Privacy Policy</a>
            <a href="{{ route('get.termsAndCondition.page') }}">Terms and Conditions</a>
            <a href="{{ route('get.help.page') }}">Help & Support</a>
        </div>
    </div>
</body>
</html>

