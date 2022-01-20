<div class='form-group col-md-8 offset-md-2 container_fileupload py-3'>
    <div class='px-3' id='fileUploadForm'>
        <input type='file' class='my-3' value='' name='{{$input_name}}'>
        <button type='submit' class='btn btn-primary my-3' onclick='onFileSubmitClick()'>{{$submit_button_text}}</button>
    </div>
    <div class='spinner-border' id='fileLoadingSpinner'></div>
</div>

<script>
    $('#fileLoadingSpinner').hide();

    function onFileSubmitClick() {
        $('#fileLoadingSpinner').show();
        $('#fileUploadForm').hide();
    }
</script>