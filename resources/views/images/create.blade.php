@extends('layouts.master')
<head>
    <title>Image Concepts</title>
</head>
 @section('content')
    <h1>Image Concepts</h1>
    <h3>This is where we will allow the user to upload image(-s)</h3>
    
    <form method="POST" action="{{route('image')}}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group" >
            <label for="image">Enter your filename.</label>
            <input type="file" name="image" id="image" >
        </div>
         <input type="submit" value="Submit" style="margin-top: 30px;">
    </form>
@stop