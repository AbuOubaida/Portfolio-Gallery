@extends('layouts/main')
@section('content')
    <?php
    $user = \Illuminate\Support\Facades\Auth::user();
    ?>
    <div class="callout profile-cover-bg">
        <div class="cover-image-section" style='background-image: linear-gradient(rgba(0, 0, 255, 0.5), rgba(255, 255, 0, 0.5)), url("{{url('/')}}/images/cover_profile/{{$user->cover_image}}")'>
            <div class="grid-x grid-margin-x">
                <div class="medium-2">
                    <img class="thumbnail profile-pic-lg" src="{{url('/')}}/images/profile/{{$user->profile_pic}}">
                </div>
                <div class="medium-5">
                    <div class="position-absolute profile-title-sec">
                        <h3 class="profile-name">{{$user->name}}</h3>
                        <p class="roll">@if($user->position == 1){{'@Admin'}}@elseif($user->position == 2){{'@Editor'}}@endif</p>
                        <a href="profile/edit/{{$user->id}}"><button class="button bg-success edit-profile-btn"><i class="fa fa-edit"></i> Edit Profile</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <article class="grid-container">
        @if(count($gallery))
        <div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-4">
            @foreach($gallery as $g)
            <div class="cell">
                <a href="profile/gallery/show/{{$g->id}}">
                    <img class="thumbnail cover-image" src="images/{{$g->cover_image}}">
                </a>
                <h5>{{$g->name}}</h5>
                <p>{{substr($g->description,0,20)}}......<a href="{{url('/')}}/profile/gallery/show/{{$g->id}}">More</a></p>
            </div>
            @endforeach
        </div>
        @else
            <h5 class="text-center font-bold"> Not Found!</h5>
        @endif
        <hr>
    </article>
@stop
