@if (Request::url() === url('/') && !Session::has('current_order'))
<div class='image_background w-100 p-0 m-0' style="background-image: url({{asset('img/main-cover.jpg')}});">
    <div class='w-100 container_bluetranslucent'>
        <div class='container text-center'>
            <div class='row py-5'>
                <div class='col py-5'>
                    <h1 class='text-uppercase text_white font-weight-bold mb-3'>Wholesale Management Platform</h1>
                    <h4 class='text_white font-weight-bold'>{{__('main.short_description')}}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endif