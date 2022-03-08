<div>
    <button class='btn btn-link link_red' id='confirm_delete_modal_button'>{{$button_text}}</button>
    <br><small class='text_red'>{{$message}}</small>
</div>

<div class='modal hide fade' id='confirm_delete_modal'>
    <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content text-left'>
            <div class='modal-body py-3'>
                
                <h4 class='font-weight-bold py-3'>{{$modal_header}}</h4>
                <h5>{{$modal_message}}</h5>

                <div class='text-right py-2' id='confirm_delete_modal_form'>
                    <form action='{{ action($action, $action_param)}}' method='POST'>
                        <input type='hidden' name='_method' value='DELETE'>
                        {{csrf_field()}}
                        <button class='btn btn-link link_main' data-dismiss='modal'>CANCEL</button>
                        <button type='submit' id='confirm_delete_modal_submit' class='btn btn-danger'>DELETE</button>
                    </form>
                </div>
                <div class='text-center py-3' id='confirm_delete_modal_spinner'>
                    <div class='spinner-border text-center'></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/scripts/confirm-delete.js') }}"></script>