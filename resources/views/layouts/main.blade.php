
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Project | Welcome</title>
    <link rel="stylesheet" href="{{ asset('assets/css/foundation.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/motion-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/foundation-prototype.min.css') }}">
    <link href='{{ asset('assets/css/foundation-icons.css') }}' rel='stylesheet' type='text/css'>
    <link href='{{ asset('assets/css/main.css') }}' rel='stylesheet' type='text/css'>
    <!-- optional CDN for Foundation Icons ^^ -->
</head>
<body>
<div class="off-canvas position-left reveal-for-large" id="my-info" data-off-canvas>
    <div class="grid-y grid-padding-x" style="height: 100%;">
        <br>
        <?php
        if (\Illuminate\Support\Facades\Auth::check()):
        $user = \Illuminate\Support\Facades\Auth::user();
        ?>
        <div class="cell shrink text-center">
            <img class="thumbnail profile-pic" src="{{url('/')}}/images/profile/{{$user->profile_pic}}">
            <div class="text-info">{{$user->name}}</div>
        </div>
        <?php
        endif;
        ?>
        <ul class="side-nav mynav">
            <li><a href="{{ asset('/') }}">Home</a></li>
            <?php
            if (\Illuminate\Support\Facades\Auth::check()):
                ?>
            <li><a href="{{ asset('/') }}gallery/create">Create Gallery</a></li>
            <li><a href="{{ asset('/') }}profile">Profile</a></li>
            <li><a href="{{ asset('/') }}logout">Log Out</a></li>
            <?php
            else:
            ?>
            <li><a href="{{ asset('/') }}login">Login</a></li>
            <li><a href="{{ asset('/') }}register">Register</a></li>
            <?php
            endif;
            ?>
        </ul>

    </div>
</div>

<div class="off-canvas-content" data-off-canvas-content>
    <div class="title-bar hide-for-large">
        <div class="title-bar-left">
            <button class="menu-icon" type="button" data-toggle="my-info"></button>
            <span class="title-bar-title">Mike Mikerson</span>
        </div>
    </div>
    @if(\Session::has('message'))
        <div data-alert class="alert-box">
            {{Session::get('message')}}
        </div>
    @elseif(\Session::has('error'))
        <div data-alert class="alert-box text-danger">
            {{Session::get('error')}}
        </div>
    @endif
    @if($errors->any('profile_pic'))
        <div data-alert class="alert-box text-danger">
            {{$errors->first('profile_pic')}}
        </div>
    @endif
    @if($errors->any('cover_image'))
        <div data-alert class="alert-box text-danger">
            {{$errors->first('cover_image')}}
        </div>
    @endif
    @if($errors->any('oldPassword'))
        <div data-alert class="alert-box text-danger">
            {{$errors->first('oldPassword')}}
        </div>
    @endif
    @if($errors->any('newPassword'))
        <div data-alert class="alert-box text-danger">
            {{$errors->first('newPassword')}}
        </div>
    @endif
    @if($errors->any('conPassword'))
        <div data-alert class="alert-box text-danger">
            {{$errors->first('conPassword')}}
        </div>
    @endif
    @yield('content')
</div>

<script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('assets/js/foundation.min.js') }}"></script>
<script src="{{ asset('assets/js/motion-ui.min.js') }}"></script>
<script src='{{ asset('assets/js/a076d05399.js') }}' crossorigin='anonymous'></script>
<script src='{{ asset('assets/js/main.js') }}' crossorigin='anonymous'></script>
<script>
    $(document).foundation();
</script>
</body>
</html>


