@extends('layouts/main')
@section('content')
    <?php
    $user = \Illuminate\Support\Facades\Auth::user();
    ?>
    <div class="callout profile-cover-bg">
        <div class="cover-image-section" id="editCoverImg" style='background-image: linear-gradient(rgba(0, 0, 255, 0.5), rgba(255, 255, 0, 0.5)), url("{{url('/')}}/images/cover_profile/{{$user->cover_image}}")'>
            <div class="grid-x grid-margin-x">
                <div class="medium-2">
                    <img class="thumbnail profile-pic-lg" id="editProfilePic" src="{{url('/')}}/images/profile/{{$user->profile_pic}}">
                </div>
            </div>
        </div>
        {{--        <article class="grid-container">--}}
        {{--            <div class="">--}}
        {{--                <h1>Portfolio Gallery</h1>--}}
        {{--                <p class="lead">Please Click My Portfolio Cover Image to see all Project</p>--}}
        {{--                <a href="{{url('/')}}/gallery/create/" class="button"><i class="fa fa-edit"></i> Create Gallery</a>--}}
        {{--            </div>--}}
        {{--        </article>--}}
    </div>
    <article class="grid-container text-center">
        <h3>
            Change You Password here
        </h3>
        <div class="grid-x grid-margin-x">
            <div class="maindiv" style="margin-left: 35%">
                {!! Form::open(['action' => 'ProfileController@updatePassword','enctype'=>'multipart/form-data','method'=>'post'])!!}

                {!! Form::label('oldPassword', 'Old Password') !!}
                {!! Form::password('oldPassword',$attributes=['placeholder'=>'Enter Your Old Password','type'=>'password','name'=>'oldPassword','required'=>'required', 'class'=>'form-control','id'=>'oldPassword']) !!}

                {!! Form::label('newPassword', 'New Password') !!}
                {!! Form::password('newPassword',$attributes=['placeholder'=>'Enter Your New Password','name'=>'newPassword','required'=>'required', 'class'=>'form-control','id'=>'newPassword']) !!}

                {!! Form::label('conPassword', 'Conform New Password') !!}
                {!! Form::password('conPassword',$attributes=['placeholder'=>'Enter Your Conform New Password','name'=>'conPassword','required'=>'required', 'class'=>'form-control','id'=>'conPassword']) !!}

                {!! Form::submit('Update',$attributes=['class'=>'button']) !!}
                {!! Form::close() !!}
            </div>
        </div>

        <hr>


    </article>
@stop
