@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row'>
        <div class='col container_grey py-5'>
            <h1 class='text-uppercase pb-3'>IMPORT COMPLETED</h1>

            @if ($importResults['errors']->count() > 0)
                <div class='text-left pt-4 pb-2'>
                    <h2 class='font-weight-bold'>Errors ({{$importResults['errors']->count()}})</h2>
                    <h5>The following records have not been imported. Please fix the data and try again.</h5>
                </div>
                <table class="table table_main table_error">
                    <tr><th>Row Number</th><th>Error</th></tr>
                    @foreach ($importResults['errors'] as $key=>$error_messages)
                        @if ($error_messages->count() > 0)
                            <tr>
                                <td>{{ $key }}</td>
                                <td>
                                @foreach ($error_messages as $error_message)
                                    {{ $error_message }} <br>
                                @endforeach
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            @endif

            @if ($importResults['info']->count() > 0)
                <div class='text-left pt-4 pb-2'>
                    <h2 class='text-left font-weight-bold'>Successful Imports ({{$importResults['info']->count()}})</h2>
                </div>
                <table class="table table_main">
                    <tr><th>Row Number</th><th>Result</th></tr>
                    @foreach ($importResults['info'] as $key=>$info_messages)
                        @if ($info_messages->count() > 0)
                            <tr>
                                <td>{{ $key }}</td>
                                <td>
                                @foreach ($info_messages as $info_message)
                                    {{ $info_message }} <br>
                                @endforeach
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            @endif

        </div>
    </div>
</div>
@endsection
