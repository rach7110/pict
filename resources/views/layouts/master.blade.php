<!DOCTYPE html>
 <html>
 <head>
    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- EXTERNAL SCRIPTS -->
    <script src="{{ URL::asset('js/script.js') }}"></script>
    <!-- JQUERY FOR BOOTSTRAP -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <!-- CSS FOR BOOTSTRAP -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- FONT AWESOME --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Kodchasan" rel="stylesheet" type="text/css">
     <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css') }}">
     <meta name="HandheldFriendly" content="true">
    <meta name="viewport" content="width=device-width, initial-scale=0.666667, maximum-scale=0.666667, user-scalable=0">
    <meta name="viewport" content="width=device-width">
</head>
<body class=".accent-5">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="/">
                    <span class="accent-1">P</span>
                    <span class="accent-2">i</span>
                    <span class="accent-3">c</span>
                    <span class="accent-4">t</span>
                </a>
                <!-- MENU TOGGLE BUTTON -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    {{-- Hamburger icon. --}}
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
             <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav right">
                    <li><a href="{{route('recognition.full')}}"><span class="accent-1">Full</span></a></li>
                    <li><a href="{{route('recognition.concept')}}"><span class="accent-1">Concept</span></a></li>
                </ul>
            </div>
         </div><!-- /.container-fluid -->
    </nav>
    <div class="container">
        {{--  Error Messages --}}
        @if(count($errors)>0)
        <div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- FLASH MESSAGES -->
        @if(Session::has('message'))
        <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
        @endif

        @yield('content')
    </div>
</body>