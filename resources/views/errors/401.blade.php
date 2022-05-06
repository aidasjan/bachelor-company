@include('errors.http_error', [
    'error_code' => '401', 
    'error_message' => 'Unauthorized', 
    'additional_message' => 'There was a problem while verifying your identity. Try to log in again.'
])
