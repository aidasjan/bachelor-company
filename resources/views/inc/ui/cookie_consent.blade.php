<div class="modal hide fade" id="cookie_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body py-4">
                <h3 class='py-2'>Welcome!</h3>
                <b>We use cookies to provide best possible user experience. By using this service you agree to our Privacy Policy.</b><br>
            </div>
            <div class="modal-footer border-0">
                <a href="{{url('/privacy-policy')}}" class='link_main mx-3'>Learn More</a>
                <a href="{{url('/cookies-agree')}}"><button id="button_cookies_agree" type="button" class="btn btn-primary">CONTINUE</button></a>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/scripts/cookie-consent.js') }}"></script>