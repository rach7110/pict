@extends('layouts.master')
<head>
    <title>Image Concepts</title>
</head>
@section('content')
    @if(isset($results))
    <h3>Here are the results:</h3>
    @foreach($results as $result)
        @php $result = json_decode($result) @endphp
        <div class="row">
            <p>{{$result->id}}</p>
            <hr/>
            @foreach($result->data as $concept)
                <p>{{$concept->name}}: {{$concept->value}}</span></p>
            @endforeach
        </div>
    @endforeach
    @endif
@stop