@if (!Auth::guest() && Auth::user()->isClient() && Session::has('current_order'))
    <div class='w-100 container_grey'>
        <div class='container'>
            <div class='row py-1'>
                <div class='col-md py-3'>
                    <h5 class='my-0'>{{ __('main.now_placing') }}: <b class='text-uppercase'>{{ __('main.order') }} {{session('current_order')}}</b></h5>
                </div>
                <div class='col-md text-right py-3'>
                    <a href="{{url('/orders'.'/'.session('current_order'))}}" class='h5 link_main my-0 mx-3 text-uppercase'>{{ __('main.view_order') }}</a>
                    <a href="{{url('/orders/cancel')}}" class='h5 link_grey my-0 mx-3 text-uppercase'>{{ __('main.cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
@endif