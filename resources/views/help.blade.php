<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support - {{ config('variables.systemName') }}</title>
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
        .faq-item {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }
        .faq-question {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .faq-answer {
            color: #495057;
        }
        .section-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="logo-container">
                <img src="{{ asset('images/logo/logo-1.png') }}" alt="{{ config('variables.systemName') }} Logo">
            </div>
            <h1 style="color: white; margin-top: 1rem;">Help & Support</h1>
        </div>
    </div>

    <div class="container">
        <div class="content-card">
            <h2>Welcome to {{ config('variables.systemName') }} Help Center</h2>
            <p>We're here to help you get the most out of our platform. Whether you're a client looking for editing services or an editor offering your skills, this guide will help you navigate and use our Platform effectively.</p>

            <h2>Getting Started</h2>
            <div class="section-box">
                <h3>For Clients</h3>
                <ol>
                    <li><strong>Create an Account:</strong> Sign up with your email address and create a secure password.</li>
                    <li><strong>Complete Your Profile:</strong> Add information about yourself or your business to help editors understand your needs.</li>
                    <li><strong>Post Your First Job:</strong> Click "Post a Job" and provide details about the editing work you need, including budget and deadline.</li>
                    <li><strong>Review Proposals:</strong> Browse proposals from editors and review their portfolios and ratings.</li>
                    <li><strong>Hire an Editor:</strong> Select the editor that best fits your needs and accept their proposal.</li>
                    <li><strong>Upload Materials:</strong> Share files, videos, or images that need to be edited.</li>
                    <li><strong>Communicate:</strong> Use the messaging system to discuss project details and provide feedback.</li>
                    <li><strong>Review Work:</strong> Review completed work and request revisions if needed.</li>
                    <li><strong>Complete Job:</strong> Accept the completed work to release payment to the editor.</li>
                </ol>
            </div>

            <div class="section-box">
                <h3>For Editors</h3>
                <ol>
                    <li><strong>Create an Account:</strong> Sign up and select "Editor" as your account type.</li>
                    <li><strong>Set Up Your Profile:</strong> Add your skills, experience, education, portfolio, and set your hourly rate.</li>
                    <li><strong>Complete Stripe Onboarding:</strong> Set up your payment account to receive payments.</li>
                    <li><strong>Browse Jobs:</strong> Explore available jobs that match your skills and interests.</li>
                    <li><strong>Submit Proposals:</strong> Write compelling proposals explaining why you're the right fit for the job.</li>
                    <li><strong>Get Hired:</strong> Wait for clients to review and accept your proposal.</li>
                    <li><strong>Access Materials:</strong> Once hired, access the files and materials uploaded by the client.</li>
                    <li><strong>Complete the Work:</strong> Edit the materials according to the job requirements.</li>
                    <li><strong>Upload Completed Work:</strong> Upload the finished work to the Platform.</li>
                    <li><strong>Get Paid:</strong> Once the client accepts the work, payment is transferred to your account.</li>
                </ol>
            </div>

            <h2>Frequently Asked Questions</h2>

            <h3>For Clients</h3>
            <div class="faq-item">
                <div class="faq-question">How do I post a job on the Platform?</div>
                <div class="faq-answer">Navigate to your dashboard and click "Post a Job." Fill in the job title, description, budget, deadline, and any specific requirements. You can also upload reference materials or examples. Once posted, editors will be able to view and submit proposals.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">How do I choose the right editor for my project?</div>
                <div class="faq-answer">Review editor profiles, portfolios, ratings, and reviews from previous clients. Look for editors with experience in your specific type of project. You can also message editors before hiring to discuss your project in detail.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">How does payment work?</div>
                <div class="faq-answer">When you accept an editor's proposal, the agreed payment amount is transferred to the Platform and held in escrow. The payment is securely processed through Stripe. Once you accept the completed work, the payment is released to the editor. If you cancel the job, the payment is returned to you.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Can I request revisions?</div>
                <div class="faq-answer">Yes! You can request revisions if the work doesn't meet your requirements. Communicate clearly with the editor about what changes you need. Editors are expected to make reasonable revisions to ensure your satisfaction.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">What if I'm not satisfied with the work?</div>
                <div class="faq-answer">First, communicate with the editor to request revisions. If issues persist, contact our support team. We'll help mediate the situation and work towards a resolution. Your payment remains in escrow until you accept the work.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">How do I upload files for the editor?</div>
                <div class="faq-answer">Once you've hired an editor, you can upload files through the job page. Click "Upload Files" and select the files from your device. Supported formats include video files (MP4, MOV, AVI), image files (JPG, PNG), and other common media formats.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Can I cancel a job after hiring an editor?</div>
                <div class="faq-answer">Yes, you can cancel a job. If cancelled before work begins, you'll receive a full refund. If work has already started, refund terms will depend on the amount of work completed. Contact support for assistance with cancellations.</div>
            </div>

            <h3>For Editors</h3>
            <div class="faq-item">
                <div class="faq-question">How do I create an attractive profile?</div>
                <div class="faq-answer">Include a professional photo, detailed biography, list your skills and experience, add your education background, upload portfolio samples, set a competitive hourly rate, and add your location. A complete profile increases your chances of being hired.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">How do I write a winning proposal?</div>
                <div class="faq-answer">Read the job description carefully, highlight relevant experience, explain your approach to the project, address specific requirements mentioned by the client, provide a realistic timeline, and set a competitive price. Personalize each proposal rather than using templates.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">How do I get paid?</div>
                <div class="faq-answer">You must complete Stripe onboarding to receive payments. Once a client accepts your completed work, the payment is automatically transferred to your Stripe account. Payments are typically processed within a few business days.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">What should I do if a client requests revisions?</div>
                <div class="faq-answer">Review the revision requests carefully, communicate with the client to clarify any unclear points, make the requested changes promptly, and maintain a professional attitude. Reasonable revisions are part of providing quality service.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">How many jobs can I work on at once?</div>
                <div class="faq-answer">There's no strict limit, but it's important to manage your workload effectively. Only accept jobs you can complete on time with high quality. Overcommitting can lead to missed deadlines and negative reviews.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">What if a client cancels a job?</div>
                <div class="faq-answer">If a job is cancelled before you start work, you won't receive payment. If work has already begun, you may be compensated for completed work depending on the cancellation terms. Contact support if you have questions about a cancellation.</div>
            </div>

            <div class="faq-item">
                <div class="faq-question">How do I build my reputation on the Platform?</div>
                <div class="faq-answer">Deliver high-quality work on time, communicate professionally and promptly, be responsive to revision requests, maintain a complete and updated profile, and encourage satisfied clients to leave reviews. Positive reviews and ratings help you get more jobs.</div>
            </div>

            <h2>Payment & Billing</h2>
            <div class="section-box">
                <h3>Payment Process</h3>
                <ul>
                    <li><strong>For Clients:</strong> Payments are made through Stripe when you accept a proposal. The amount is held in escrow until you accept the completed work.</li>
                    <li><strong>For Editors:</strong> You receive payment after the client accepts your completed work. Payments are transferred to your Stripe account.</li>
                    <li><strong>Escrow System:</strong> All payments are held securely in escrow to protect both clients and editors.</li>
                    <li><strong>Refunds:</strong> If a job is cancelled, refunds are processed according to our cancellation policy.</li>
                </ul>
            </div>

            <h2>Communication</h2>
            <div class="section-box">
                <p>Effective communication is key to successful projects. Use the Platform's messaging system to:</p>
                <ul>
                    <li>Discuss project requirements and expectations</li>
                    <li>Share updates on work progress</li>
                    <li>Request and provide feedback</li>
                    <li>Clarify any questions or concerns</li>
                    <li>Coordinate revisions and changes</li>
                </ul>
                <p><strong>Tip:</strong> Keep all project-related communication on the Platform for record-keeping and dispute resolution.</p>
            </div>

            <h2>File Management</h2>
            <div class="section-box">
                <h3>Uploading Files</h3>
                <ul>
                    <li>Supported formats: MP4, MOV, AVI, JPG, PNG, and other common media formats</li>
                    <li>Maximum file size limits apply (check Platform specifications)</li>
                    <li>Organize files in folders for better management</li>
                    <li>Ensure files are properly named and organized</li>
                </ul>

                <h3>Downloading Completed Work</h3>
                <ul>
                    <li>Completed work is available for download from the job page</li>
                    <li>Download files promptly to avoid expiration</li>
                    <li>Review files before accepting the job</li>
                </ul>
            </div>

            <h2>Dispute Resolution</h2>
            <div class="section-box">
                <p>If you encounter a dispute with another user:</p>
                <ol>
                    <li><strong>Communicate First:</strong> Try to resolve the issue through direct communication.</li>
                    <li><strong>Contact Support:</strong> If direct communication doesn't work, contact our support team.</li>
                    <li><strong>Provide Details:</strong> Share all relevant information, including messages, files, and transaction details.</li>
                    <li><strong>Wait for Resolution:</strong> Our support team will review the dispute and make a fair determination.</li>
                </ol>
                <p>Our support team is committed to fair and timely dispute resolution.</p>
            </div>

            <h2>Account Security</h2>
            <div class="section-box">
                <p>Protect your account by:</p>
                <ul>
                    <li>Using a strong, unique password</li>
                    <li>Not sharing your account credentials</li>
                    <li>Logging out when using shared devices</li>
                    <li>Reporting suspicious activity immediately</li>
                    <li>Keeping your contact information up to date</li>
                </ul>
            </div>

            <h2>Tips for Success</h2>
            <div class="section-box">
                <h3>For Clients</h3>
                <ul>
                    <li>Write clear, detailed job descriptions</li>
                    <li>Set realistic budgets and deadlines</li>
                    <li>Review editor portfolios before hiring</li>
                    <li>Communicate your expectations clearly</li>
                    <li>Provide timely feedback</li>
                    <li>Leave honest reviews after project completion</li>
                </ul>

                <h3>For Editors</h3>
                <ul>
                    <li>Keep your profile updated and professional</li>
                    <li>Submit personalized, thoughtful proposals</li>
                    <li>Deliver work on time or communicate delays early</li>
                    <li>Maintain professional communication</li>
                    <li>Be responsive to revision requests</li>
                    <li>Build a strong portfolio with diverse samples</li>
                </ul>
            </div>

            <h2>Contact Support</h2>
            <div class="section-box">
                <p>If you need additional help or have questions not covered in this guide, please contact our support team through the Platform's support system. We're here to help you succeed!</p>
                <p><strong>Response Time:</strong> We aim to respond to all support requests within 24-48 hours during business days.</p>
            </div>
        </div>

        <div class="footer-links">
            <a href="{{ route('get.privacyPolicy.page') }}">Privacy Policy</a>
            <a href="{{ route('get.termsAndCondition.page') }}">Terms and Conditions</a>
            <a href="{{ route('get.help.page') }}">Help & Support</a>
        </div>
    </div>
</body>
</html>

