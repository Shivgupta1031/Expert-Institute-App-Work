<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Add your additional CSS styles and animations here */
        .welcome-container {
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            animation: fadeInUp 1s ease-out;
        }

        .app-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="welcome-container text-center p-4">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ env('APP_NAME') }}" class="app-icon mb-4">
                    <h1 class="text-primary mb-4">Privacy Policy</h1>
                    <p class="mb-4">This privacy policy is applicable to the {{ env('APP_NAME') }} app (hereinafter
                        referred to as "Application")
                        for mobile devices, which was developed by SH Developer (hereinafter referred to as "Service
                        Provider") as an Ad Supported service. This service is provided "AS IS".</p>

                    <h2 class="text-primary mb-3">What information does the Application obtain and how is it used?</h2>
                    <h3 class="mb-2">User Provided Information</h3>
                    <p class="mb-4">The Application acquires the information you supply when you download and register
                        the Application. Registration with the Service Provider is not mandatory. However, bear in mind
                        that you might not be able to utilize some of the features offered by the Application unless you
                        register with them.</p>
                    <p>The Service Provider may also use the information you provided them to contact you from time to
                        time to provide you with important information, required notices and marketing promotions.</p>

                    <h3 class="mb-2">Automatically Collected Information</h3>
                    <p class="mb-4">In addition, the Application may collect certain information automatically,
                        including, but not limited to, the type of mobile device you use, your mobile device's unique
                        device ID, the IP address of your mobile device, your mobile operating system, the type of
                        mobile Internet browsers you use, and information about the way you use the Application.</p>

                    <h3 class="mb-2">Does the Application collect precise real-time location information of the
                        device?</h3>
                    <p class="mb-4">This Application does not gather precise information about the location of your
                        mobile device.</p>

                    <div class="mb-4">
                        <p>This Application collects your device's location, which helps the Service Provider determine
                            your approximate geographical location and make use of in below ways:</p>
                        <ul>
                            <li>Geolocation Services: The Service Provider utilizes location data to provide features
                                such as personalized content, relevant recommendations, and location-based services.
                            </li>
                            <li>Analytics and Improvements: Aggregated and anonymized location data helps the Service
                                Provider to analyze user behavior, identify trends, and improve the overall performance
                                and functionality of the Application.</li>
                            <li>Third-Party Services: Periodically, the Service Provider may transmit anonymized
                                location data to external services. These services assist them in enhancing the
                                Application and optimizing their offerings.</li>
                        </ul>
                    </div>

                    <h3 class="mb-2">Do third parties see and/or have access to information obtained by the
                        Application?</h3>
                    <p class="mb-4">Only aggregated, anonymized data is periodically transmitted to external services
                        to aid the Service Provider in improving the Application and their service. The Service Provider
                        may share your information with third parties in the ways that are described in this privacy
                        statement.</p>

                    <div class="mb-4">
                        <p>Please note that the Application utilizes third-party services that have their own Privacy
                            Policy about handling data. Below are the links to the Privacy Policy of the third-party
                            service providers used by the Application:</p>
                        <ul>
                            <li><a href="https://www.google.com/policies/privacy/" target="_blank"
                                    rel="noopener noreferrer">Google Play Services</a></li>
                            <li><a href="https://support.google.com/admob/answer/6128543?hl=en" target="_blank"
                                    rel="noopener noreferrer">AdMob</a></li>
                            <li><a href="https://firebase.google.com/support/privacy" target="_blank"
                                    rel="noopener noreferrer">Google Analytics for Firebase</a></li>
                            <li><a href="https://firebase.google.com/support/privacy/" target="_blank"
                                    rel="noopener noreferrer">Firebase Crashlytics</a></li>
                            <li><a href="https://www.facebook.com/about/privacy/update/printable" target="_blank"
                                    rel="noopener noreferrer">Facebook</a></li>
                            <li><a href="https://unity3d.com/legal/privacy-policy" target="_blank"
                                    rel="noopener noreferrer">Unity</a></li>
                            <li><a href="https://onesignal.com/privacy_policy" target="_blank"
                                    rel="noopener noreferrer">One Signal</a></li>
                            <li><a href="https://www.applovin.com/privacy/" target="_blank"
                                    rel="noopener noreferrer">AppLovin</a></li>
                            <li><a href="https://www.startapp.com/privacy/" target="_blank"
                                    rel="noopener noreferrer">StartApp</a></li>
                        </ul>
                    </div>

                    <p class="mb-4">The Service Provider may disclose User Provided and Automatically Collected
                        Information:</p>
                    <ul class="mb-4">
                        <li>as required by law, such as to comply with a subpoena, or similar legal process;</li>
                        <li>when they believe in good faith that disclosure is necessary to protect their rights,
                            protect your safety or the safety of others, investigate fraud, or respond to a government
                            request;</li>
                        <li>with their trusted services providers who work on their behalf, do not have an independent
                            use of the information we disclose to them, and have agreed to adhere to the rules set forth
                            in this privacy statement.</li>
                    </ul>

                    <h2 class="text-primary mb-3">What are my opt-out rights?</h2>
                    <p class="mb-4">You can halt all collection of information by the Application easily by
                        uninstalling the Application. You may use the standard uninstall processes as may be available
                        as part of your mobile device or via the mobile application marketplace or network.</p>

                    <h2 class="text-primary mb-3">Data Retention Policy, Managing Your Information</h2>
                    <p class="mb-4">The Service Provider will retain User Provided data for as long as you use the
                        Application and for a reasonable time thereafter. The Service Provider will retain Automatically
                        Collected information for up to 24 months and thereafter may store it in aggregate. If you'd
                        like the Service Provider to delete User Provided Data that you have provided via the
                        Application, please contact them at {{ env('MAIL_FROM_ADDRESS') }} and we will respond in a
                        reasonable time. Please note that some or all of the User Provided Data may be required in order
                        for the Application to function properly.</p>

                    <h2 class="text-primary mb-3">Children</h2>
                    <p class="mb-4">The Service Provider does not use the Application to knowingly solicit data from
                        or market to children under the age of 13.</p>
                    <div class="mb-4">
                        <p>The Application does not address anyone under the age of 13. The Service Provider does not
                            knowingly collect personally identifiable information from children under 13 years of age.
                            In the case the Service Provider discover that a child under 13 has provided personal
                            information, the Service Provider will immediately delete this from their servers. If you
                            are a parent or guardian and you are aware that your child has provided us with personal
                            information, please contact the Service Provider ({{ env('MAIL_FROM_ADDRESS') }}) so that
                            they will be able to take the necessary actions.</p>
                    </div>

                    <h2 class="text-primary mb-3">Security</h2>
                    <p class="mb-4">The Service Provider is concerned about safeguarding the confidentiality of your
                        information. The Service Provider provides physical, electronic, and procedural safeguards to
                        protect information we process and maintain. For example, we limit access to this information to
                        authorized employees and contractors who need to know that information in order to operate,
                        develop or improve their Application. Please be aware that, although we endeavor provide
                        reasonable security for information we process and maintain, no security system can prevent all
                        potential security breaches.</p>

                    <h2 class="text-primary mb-3">Changes</h2>
                    <p class="mb-4">This Privacy Policy may be updated from time to time for any reason. The Service
                        Provider will notify you of any changes to the Privacy Policy by updating this page with the new
                        Privacy Policy. You are advised to consult this Privacy Policy regularly for any changes, as
                        continued use is deemed approval of all changes.</p>
                    <p class="mb-4">This privacy policy is effective as of 2024-04-18</p>

                    <h2 class="text-primary mb-3">Your Consent</h2>
                    <p class="mb-4">By using the Application, you are giving your consent to the Service Provider
                        processing of your information as set forth in this Privacy Policy now and as amended by us.
                        "Processing,” means using cookies on a computer/handheld device or using or touching information
                        in any way, including, but not limited to, collecting, storing, deleting, using, combining and
                        disclosing information.</p>

                    <h2 class="text-primary mb-3">Contact Us</h2>
                    <p>If you have any questions regarding privacy while using the Application, or have questions about
                        the practices, please contact the Service Provider via email at {{ env('MAIL_FROM_ADDRESS') }}.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
