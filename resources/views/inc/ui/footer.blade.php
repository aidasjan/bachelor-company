<div class='py-4 w-100 footer_main'>
    <div class='container'>
        <div class='row'>
            <div class='col text-center'>
                <div class='py-2'>
                    <b>© {{date('Y')}} {{config('custom.company_info.name')}}</b> <b class='mx-2'>|</b> <a href='https://{{config('custom.company_info.webpage')}}' class='link_lightblue'>{{config('custom.company_info.webpage')}}</a><br>
                </div>
                <div class='pb-1'>
                    <b>{{config('custom.company_info.email')}}</b> <b class='mx-2'>|</b> <b>{{config('custom.company_info.phone')}}</b><br>
                </div>
                <div class='py-2'>
                    <a href="{{url('/privacy-policy')}}" class='link_lightblue'>{{__('main.privacy_policy')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>