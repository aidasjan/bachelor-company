@extends('layouts.app')

@section('content')
    <div class='container'>
        <div class='row py-5'>
            <div class='col'>
                <h1 class='text-uppercase'>PRIVACY POLICY</h1>
            </div>
        </div>
        <div class='row'>
            <div class='col text-left'>
                <p>WMP, accessible from WMP.local, is a web service provided by 'WMP' located at Lithuania, European Union. Learn more about our company here: <a href='https://www.wmp.local' class='link_main'>www.wmp.local</a></p>
                <p>This privacy policy will explain how our organization uses the personal data we collect from you when you use our website.</p>
                <b>Topics:</b>
                <ul>
                    <li>What data do we collect?</li>
                    <li>How do we collect your data?</li>
                    <li>How will we use your data?</li>
                    <li>How do we store your data?</li>
                    <li>Marketing</li>
                    <li>What are your data protection rights?</li>
                    <li>What are cookies?</li>
                    <li>How do we use cookies?</li>
                    <li>What types of cookies do we use?</li>
                    <li>How to manage your cookies</li>
                    <li>Privacy policies of other websites</li>
                    <li>Changes to our privacy policy</li>
                    <li>How to contact us</li>
                    <li>Consent</li>
                </ul>
                
                <h2 class='pt-3'>What data do we collect?</h2>
                <p>We collect the following data about every user of our website:</p>
                <ul>
                    <li>Log data (internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks)</li>
                    <li>Language preferences</li>
                </ul>
                <p>If you are registered as our client, we collect the following data:</p>
                <ul>
                    <li>Personal identification information (name, email address)</li>
                    <li>Information that you submit to our website (your orders' information)</li>
                </ul>

                <h2 class='pt-3'>How do we collect your data?</h2>
                <p>You directly provide WMP with most of the data we collect. We collect data and process data when you:</p>
                <ul>
                    <li>Place an order for any of our products or services</li>
                    <li>Voluntarily complete a customer survey or provide feedback on any of our message boards or via email</li>
                    <li>Use or view our website via your browser's cookies</li>
                    <li>Request for your personal WMP account by contacting us directly</li>
                </ul>
                <p>Furthermore, you do not have an ability to register yourself to our website. Only we can perform this action if you request it. However, we would never register you to our service and use your personal data without your consent.</p>
                <p>You can only get your personal WMP account by contacting our company directly and providing us with required data.</p>

                <h2 class='pt-3'>How we will use your data?</h2>
                <p> WMP collects your data so that we can:</p>
                <ul>
                    <li>Process your orders and manage your account</li>
                    <li>Email you with confirmation of orders that you make</li>
                    <li>Email you with special offers on other products and services we think you might like</li>
                </ul>

                <h2 class='pt-3'>How do we store your data?</h2>
                <p> WMP securely stores your data at server in Lithuania, European Union. Your personally identifiable data in our database is always encrypted by advanced hashing and encryption algorithms.</p>
                <p> WMP will keep your name, email and the data you submit to our website as long as you periodically use our service. If you stop using our service for 2 years, we might delete all your data by removing it from our database.</p>

                <h2 class='pt-3'>Marketing</h2>
                <p> WMP would like to send you information about products and services of ours that we think you might like, as well as those of our partner companies. If you have agreed to receive marketing, you may always opt out at a later date. You have the right at any time to stop WMP from contacting you for marketing purposes.</p>
                
                <h2 class='pt-3'>What are your data protection rights?</h2>
                <p> WMP would like to make sure you are fully aware of all of your data protection rights. Every user is entitled to the following:</p>
                <p>The right to access – You have the right to request WMP for copies of your personal data. We may charge you a small fee for this service.</p>
                <p>The right to rectification – You have the right to request that WMP correct any information you believe is inaccurate. You also have the right to request WMP to complete the information you believe is incomplete.</p>
                <p>The right to erasure – You have the right to request that WMP erase your personal data, under certain conditions.</p>
                <p>The right to restrict processing – You have the right to request that WMP restrict the processing of your personal data, under certain conditions.</p>
                <p>The right to object to processing – You have the right to object to WMP’s processing of your personal data, under certain conditions.</p>
                <p>The right to data portability – You have the right to request that WMP transfer the data that we have collected to another organization, or directly to you, under certain conditions.</p>
                <p>If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us at our email: <b>{{config('custom.company_info.email')}}</b></p>
                <p>Or Call us at: <b>+370</b></p>

                <h2 class='pt-3'>Cookies</h2>
                <p>Cookies are text files placed on your computer to collect standard Internet log information and visitor behavior information. When you visit our websites, we may collect information from you automatically through cookies or similar technology.</p>
                
                <h2 class='pt-3'>How do we use cookies?</h2>
                <p>Our Company uses cookies in a range of ways to improve your experience on our website, including:</p>
                <ul>
                    <li>Keeping you signed in</li>
                    <li>Understanding how you use our website</li>
                    <li>Keeping your language and other preferences</li>
                </ul>

                <h2 class='pt-3'>What types of cookies do we use?</h2>
                <p>There are a number of different types of cookies, however, our website uses only Functionality cookies – WMP uses these cookies so that we recognize you on our website and remember your previously selected preferences. These could include what language you prefer and location you are in. A mix of first-party and third-party cookies are used.</p>
            
                <h2 class='pt-3'>How to manage cookies?</h2>
                <p>You can set your browser not to accept cookies, and <a href='https://support.google.com/accounts/answer/61416' class='link_main'>this website</a> tells you how to remove cookies from your browser. However, in a few cases, some of our website features may not function as a result.</p>

                <h2 class='pt-3'>Privacy policies of other websites</h2>
                <p>This website might contain links to other websites. Our privacy policy applies only to our website, so if you click on a link to another website, you should read their privacy policy.</p>

                <h2 class='pt-3'>Changes to our privacy policy</h2>
                <p>Our Company keeps its privacy policy under regular review and places any updates on this web page. This privacy policy was last updated on January 6th, 2021.</p>

                <h2 class='pt-3'>How to contact us</h2>
                <p>If you have any questions about WMP privacy policy, the data we hold on you, or you would like to exercise one of your data protection rights, please do not hesitate to contact us.</p>
                <p>Email us at: <b>{{config('custom.company_info.email')}}</b></p>
                <p>Call us: <b>{{config('custom.company_info.phone')}}</b></p>

                <h2 class='pt-3'>Consent</h2>
                <p>By using our website, you hereby consent to our Privacy Policy and agree to its Terms and Conditions.</p>

            </div>
        </div>
    </div>
@endsection