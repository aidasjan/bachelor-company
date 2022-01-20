@include('errors.http_error', [
    'error_code' => '500',
    'error_message' => 'Server Error',
    'additional_message' => 'There was an unexpected problem on our side. Please contact us directly to resolve this.'
])
