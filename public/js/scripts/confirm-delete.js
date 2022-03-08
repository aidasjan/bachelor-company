$('#confirm_delete_modal_spinner').hide();

$('#confirm_delete_modal_button').on('click', () => {
    $('#confirm_delete_modal').modal('show');
});

$('#confirm_delete_modal_submit').on('click', () => {
    $('#confirm_delete_modal_spinner').show();
    $('#confirm_delete_modal_form').hide();
});
