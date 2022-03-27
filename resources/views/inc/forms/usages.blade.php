<form method='GET' action={{ $action }}>
    <select class='form-control' name='usage'>
        @foreach ($usages as $usage)
            <option value='{{ $usage->id }}'>{{ $usage->name }}</option>
        @endforeach
    </select>
    <div class='mt-4'>
        <button type='submit' class='btn btn-primary text-uppercase'>{{$buttonText}}</button>
    </div>
</form>
