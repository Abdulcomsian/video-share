<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - {{ config('variables.systemName') }}</title>
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
            <h1 style="color: white; margin-top: 1rem;">Terms and Conditions</h1>
        </div>
    </div>

    <div class="container">
        <div class="content-card">
            {{-- <p class="effective-date"><strong>Effective Date:</strong> {{ date('F d, Y') }}</p> --}}

            <p>Welcome to {{ config('variables.systemName') }} ("Platform," "we," "us," or "our"). These Terms and Conditions ("Terms") govern your access to and use of our platform that connects clients seeking video, image, and media editing services with professional editors. By accessing or using our Platform, you agree to be bound by these Terms. If you do not agree with these Terms, please do not use our Platform.</p>

            <h2>1. Acceptance of Terms</h2>
            <p>By registering for an account, accessing, or using the Platform, you acknowledge that you have read, understood, and agree to be bound by these Terms, our Privacy Policy, and all applicable laws and regulations. If you are entering into these Terms on behalf of a company or other legal entity, you represent that you have the authority to bind such entity to these Terms.</p>

            <h2>2. Platform Overview</h2>
            <p>Our Platform facilitates connections between:</p>
            <ul>
                <li><strong>Clients:</strong> Individuals or businesses seeking video, image, or media editing services who post jobs and hire editors.</li>
                <li><strong>Editors:</strong> Professional service providers who bid on jobs, perform editing work, and deliver completed projects.</li>
            </ul>
            <p>The Platform enables clients to post jobs, receive proposals from editors, hire editors, upload attachments, communicate throughout the project, and process payments securely through Stripe.</p>

            <h2>3. User Eligibility and Registration</h2>
            <h3>3.1 Eligibility</h3>
            <p>You must be at least 18 years old to use the Platform. By using the Platform, you represent and warrant that:</p>
            <ul>
                <li>You are at least 18 years of age;</li>
                <li>You have the legal capacity to enter into these Terms;</li>
                <li>All information you provide is accurate, current, and complete;</li>
                <li>You will maintain and update your information to keep it accurate.</li>
            </ul>

            <h3>3.2 Account Registration</h3>
            <p>To access certain features, you must register for an account. You are responsible for:</p>
            <ul>
                <li>Maintaining the confidentiality of your account credentials;</li>
                <li>All activities that occur under your account;</li>
                <li>Notifying us immediately of any unauthorized use of your account;</li>
                <li>Ensuring that you log out from your account at the end of each session.</li>
            </ul>

            <h2>4. User Roles and Responsibilities</h2>
            <h3>4.1 Clients</h3>
            <p>As a Client, you agree to:</p>
            <ul>
                <li>Post clear, accurate job descriptions with specific requirements;</li>
                <li>Review editor profiles, portfolios, and proposals before hiring;</li>
                <li>Upload necessary attachments and materials in a timely manner;</li>
                <li>Communicate clearly and professionally with hired editors;</li>
                <li>Make payment through Stripe when accepting a proposal (funds are held by the platform until job completion);</li>
                <li>Review completed work and provide feedback or request revisions as needed;</li>
                <li>Complete the job acceptance process to release payment to the editor.</li>
            </ul>

            <h3>4.2 Editors</h3>
            <p>As an Editor, you agree to:</p>
            <ul>
                <li>Provide accurate information about your skills, experience, and qualifications;</li>
                <li>Submit professional and competitive proposals for jobs;</li>
                <li>Deliver high-quality work that meets or exceeds client expectations;</li>
                <li>Complete work within agreed-upon deadlines;</li>
                <li>Upload completed work to the Platform;</li>
                <li>Communicate professionally and promptly with clients;</li>
                <li>Address revision requests in a timely manner;</li>
                <li>Comply with all applicable copyright and intellectual property laws.</li>
            </ul>

            <h2>5. Job Posting and Bidding Process</h2>
            <h3>5.1 Job Posting</h3>
            <p>Clients may post jobs describing their editing needs. Job posts should include:</p>
            <ul>
                <li>Clear description of the work required;</li>
                <li>Budget or payment terms;</li>
                <li>Deadline or timeline expectations;</li>
                <li>Any specific requirements or preferences.</li>
            </ul>

            <h3>5.2 Bidding and Proposals</h3>
            <p>Editors may submit proposals (bids) on posted jobs. Proposals should include:</p>
            <ul>
                <li>Relevant experience and qualifications;</li>
                <li>Proposed approach to the project;</li>
                <li>Timeline for completion;</li>
                <li>Pricing information.</li>
            </ul>

            <h3>5.3 Hiring Process</h3>
            <p>Clients may review proposals and hire an editor. Upon hiring:</p>
            <ul>
                <li>The client must transfer the agreed payment amount to the platform (held in escrow);</li>
                <li>The job status changes to "in progress";</li>
                <li>Clients may upload attachments and materials for the editor;</li>
                <li>Both parties can communicate through the Platform's messaging system.</li>
            </ul>

            <h2>6. Payment and Escrow System</h2>
            <h3>6.1 Payment Processing</h3>
            <p>All payments are processed securely through Stripe. When a client accepts a proposal:</p>
            <ul>
                <li>The full payment amount is transferred to the platform (superadmin account) and held in escrow;</li>
                <li>Payment is held until the job is completed and accepted by the client;</li>
                <li>Upon job completion and client acceptance, the payment is transferred to the editor's account;</li>
                <li>If a job is cancelled, the payment is returned to the client.</li>
            </ul>

            <h3>6.2 Payment Terms</h3>
            <ul>
                <li>Clients are responsible for ensuring sufficient funds are available for payment;</li>
                <li>Editors will receive payment only after job completion and client acceptance;</li>
                <li>The Platform may charge service fees as disclosed at the time of transaction;</li>
                <li>All prices are in the currency specified on the Platform.</li>
            </ul>

            <h3>6.3 Refunds and Cancellations</h3>
            <p>If a job is cancelled:</p>
            <ul>
                <li>Before work begins: Full refund to client;</li>
                <li>After work begins: Refund terms will be determined based on work completed and platform policies;</li>
                <li>Disputes regarding payments will be resolved by the Platform's support team.</li>
            </ul>

            <h2>7. Work Delivery and Acceptance</h2>
            <h3>7.1 Work Upload</h3>
            <p>Editors must upload completed work to the Platform. Uploaded work should:</p>
            <ul>
                <li>Meet the specifications outlined in the job description;</li>
                <li>Be in the agreed-upon format and quality;</li>
                <li>Be free from defects and errors.</li>
            </ul>

            <h3>7.2 Revision Requests</h3>
            <p>Clients may request revisions if the work does not meet requirements. Editors agree to:</p>
            <ul>
                <li>Address reasonable revision requests;</li>
                <li>Complete revisions within agreed timeframes;</li>
                <li>Maintain professional communication during the revision process.</li>
            </ul>

            <h3>7.3 Job Completion</h3>
            <p>Upon client acceptance of the completed work:</p>
            <ul>
                <li>The job status changes to "completed";</li>
                <li>Payment is released to the editor;</li>
                <li>Both parties may leave reviews and ratings;</li>
                <li>The job is marked as successfully completed.</li>
            </ul>

            <h2>8. Communication</h2>
            <p>The Platform provides messaging functionality for communication between clients and editors. You agree to:</p>
            <ul>
                <li>Use professional and respectful language;</li>
                <li>Not share personal contact information until a job is accepted;</li>
                <li>Respond to messages in a timely manner;</li>
                <li>Keep all project-related communication on the Platform;</li>
                <li>Not use the messaging system for spam, harassment, or illegal activities.</li>
            </ul>

            <h2>9. Intellectual Property Rights</h2>
            <h3>9.1 Client Content</h3>
            <p>Clients retain ownership of all original content they upload. By uploading content, clients grant editors a limited license to use such content solely for the purpose of completing the job.</p>

            <h3>9.2 Completed Work</h3>
            <p>Upon full payment, clients receive ownership rights to the completed work, subject to any prior agreements. Editors may use completed work in their portfolios unless otherwise agreed.</p>

            <h3>9.3 Platform Content</h3>
            <p>All Platform content, including but not limited to text, graphics, logos, and software, is the property of the Platform or its licensors and is protected by copyright and other intellectual property laws.</p>

            <h2>10. Prohibited Conduct</h2>
            <p>You agree not to:</p>
            <ul>
                <li>Violate any applicable laws or regulations;</li>
                <li>Infringe upon intellectual property rights;</li>
                <li>Post false, misleading, or fraudulent information;</li>
                <li>Harass, abuse, or harm other users;</li>
                <li>Attempt to circumvent payment systems;</li>
                <li>Use the Platform for any illegal or unauthorized purpose;</li>
                <li>Interfere with or disrupt the Platform's operation;</li>
                <li>Attempt to gain unauthorized access to any part of the Platform;</li>
                <li>Upload malicious code, viruses, or harmful software;</li>
                <li>Impersonate any person or entity.</li>
            </ul>

            <h2>11. Account Termination</h2>
            <p>We reserve the right to suspend or terminate your account at any time, with or without notice, for:</p>
            <ul>
                <li>Violation of these Terms;</li>
                <li>Fraudulent or illegal activity;</li>
                <li>Non-payment or payment disputes;</li>
                <li>Abuse of the Platform or other users;</li>
                <li>Any other reason we deem necessary to protect the Platform and its users.</li>
            </ul>
            <p>Upon termination, your right to use the Platform will immediately cease, and we may delete your account and content.</p>

            <h2>12. Disclaimers and Limitation of Liability</h2>
            <h3>12.1 Platform Disclaimer</h3>
            <p>The Platform is provided "as is" and "as available" without warranties of any kind, either express or implied. We do not guarantee that:</p>
            <ul>
                <li>The Platform will be uninterrupted or error-free;</li>
                <li>Defects will be corrected;</li>
                <li>The Platform is free of viruses or other harmful components.</li>
            </ul>

            <h3>12.2 Limitation of Liability</h3>
            <p>To the maximum extent permitted by law, we shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, or use, arising out of or related to your use of the Platform.</p>

            <h2>13. Indemnification</h2>
            <p>You agree to indemnify, defend, and hold harmless the Platform, its affiliates, and their respective officers, directors, employees, and agents from any claims, damages, losses, liabilities, and expenses (including legal fees) arising out of or related to:</p>
            <ul>
                <li>Your use of the Platform;</li>
                <li>Your violation of these Terms;</li>
                <li>Your violation of any rights of another user or third party;</li>
                <li>Content you submit, post, or transmit through the Platform.</li>
            </ul>

            <h2>14. Dispute Resolution</h2>
            <p>In the event of a dispute between a client and editor:</p>
            <ul>
                <li>Parties should first attempt to resolve the dispute through direct communication;</li>
                <li>If resolution cannot be reached, parties may contact Platform support for mediation;</li>
                <li>The Platform's support team will review the dispute and make a determination;</li>
                <li>Platform decisions regarding disputes are final and binding.</li>
            </ul>

            <h2>15. Changes to Terms</h2>
            <p>We reserve the right to modify these Terms at any time. We will notify users of any material changes by:</p>
            <ul>
                <li>Posting the updated Terms on the Platform;</li>
                <li>Sending an email notification to registered users;</li>
                <li>Updating the "Effective Date" at the top of these Terms.</li>
            </ul>
            <p>Your continued use of the Platform after changes become effective constitutes acceptance of the modified Terms.</p>

            <h2>16. Governing Law</h2>
            <p>These Terms shall be governed by and construed in accordance with the laws of the jurisdiction in which the Platform operates, without regard to its conflict of law provisions.</p>

            <h2>17. Contact Information</h2>
            <p>If you have any questions about these Terms, please contact us through the Platform's support system or at the contact information provided on our Platform.</p>

            <h2>18. Severability</h2>
            <p>If any provision of these Terms is found to be unenforceable or invalid, that provision shall be limited or eliminated to the minimum extent necessary, and the remaining provisions shall remain in full force and effect.</p>

            <h2>19. Entire Agreement</h2>
            <p>These Terms, together with our Privacy Policy, constitute the entire agreement between you and the Platform regarding your use of the Platform and supersede all prior agreements and understandings.</p>
        </div>

        <div class="footer-links">
            <a href="{{ route('get.privacyPolicy.page') }}">Privacy Policy</a>
            <a href="{{ route('get.termsAndCondition.page') }}">Terms and Conditions</a>
            <a href="{{ route('get.help.page') }}">Help & Support</a>
        </div>
    </div>
</body>
</html>
