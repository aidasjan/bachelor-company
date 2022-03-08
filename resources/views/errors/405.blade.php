@include('errors.http_error', [
    'error_code' => '404', 
    'error_message' => 'Not Found', 
    'additional_message' => 'The page that you are looking for might have been moved to another place.'
])
