@extends('layouts.master')
<head>
    <title>Image Concepts</title>
</head>
 @section('content')
    <h1>Image Concepts</h1>
    <h3>This is where we will allow the user to upload image(-s)</h3>
    
    <form method="POST" action="{{route('recognition')}}" enctype="multipart/form-data">
        {{ csrf_field() }}
        
        {{--  Content to train the image recogition model. --}}
        <div class="form-group" >
            <h2>Content</h2>
            <p><strong>Description:</strong> this is the file you use to analyze all other files. </p>
            <input type="file" name="content" id="content" >


        </div>
        {{-- Image to be analyzed by model. --}}
        <div class="form-group" >
            <h2>Files</h2>
            <p><strong>Description:</strong> this is the file that is being analyzed for content. </p>
            <input type="file" name="image[]">
            <input type="file" name="image[]">
        </div>
        {{-- Submit --}}
         <input type="submit" value="Submit" style="margin-top: 30px;">
    </form>
@stop