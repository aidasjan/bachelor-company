<div class="modal hide fade" id="notification_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body py-3">
                
                @if (session()->has('success'))
                    <h3 class='text-uppercase font-weight-bold text_green py-3'>{{ __('main.success') }}</h3>
                    <h5>{{ session()->get('success') }}</h5>
                @elseif ($errors->any())
                    <h3 class='text-uppercase font-weight-bold text_red py-3'>{{ __('main.error') }}</h3>
                    @foreach ($errors->all() as $error)
                        <h5>{{ $error }}</h5>
                    @endforeach
                @endif

                <div class='text-right py-3'>
                    <button type="button" class="btn btn-secondary mx-3" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/scripts/notification.js') }}"></script>