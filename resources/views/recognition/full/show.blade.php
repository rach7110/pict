@extends('layouts.master')
<head>
    <title>Image Concepts</title>
</head>
@section('content')
    <a class="btn btn-primary" href="{{route('recognition.full')}}">New</a>
    @if(isset($results))
    <h3>Here are the results:</h3>
    @foreach($results as $result)
        <div class="row">
            <p>{{ $result->input()->id() }}</p>
            <hr/>
            @foreach ($result->data() as $concept) 
                <p>{{$concept->name()}}: {{$concept->value() }}
            @endforeach
        </div>
    @endforeach
    @endif
@stop