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
    <article class="grid-container">
        <h3>
            Edit your Profile here
        </h3>
        <small><a href="change-password/{{$user->id}}" class="">Click to change Password</a></small>
        <div class="grid-x grid-margin-x">
            <div class="maindiv">
                {!! Form::open(['action' => 'ProfileController@update','enctype'=>'multipart/form-data','method'=>'post'])!!}
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', $value ="$user->name",$attributes=['placeholder'=>'Enter Your Name','name'=>'name','required'=>'required', 'class'=>'form-control','id'=>'name']) !!}

                <div class="display-block width-50">
                    <img src="{{url('/')}}/images/profile/{{$user->profile_pic}}" alt="" width="10%" id="profileDisplay">
                </div>
                {!! Form::label('profile_pic',"$user->profile_pic (300px * 300px & .jpj/.jpeg/.png & size max 1Mb)") !!}
                {!! Form::file('profile_pic', $attributes=['onchange'=>'displayProfileImage(this)']) !!}

                <div class="display-block width-50">
                    <img src="{{url('/')}}/images/cover_profile/{{$user->cover_image}}" alt="" width="10%" id="coverDisplay">
                </div>
                {!! Form::label('cover_pic',"$user->cover_image ( 1021px * 250px & .jpj/.jpeg/.png & size max 1.5Mb)") !!}
                {!! Form::file('cover_pic',$attributes=['onchange'=>'displayCoverImage(this)']) !!}
{{--                <input type="hidden" name="photo_id" value="{{$photo->id}}">--}}

                {!! Form::submit('Update',$attributes=['class'=>'button']) !!}
                {!! Form::close() !!}
            </div>
        </div>

        <hr>


    </article>
@stop
