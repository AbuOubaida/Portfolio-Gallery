@extends('layouts/main')
@section('content')
    <div class="callout primary">
        <article class="grid-container">
            <div class="grid-x grid-margin-x">
                <div class="medium-7">
                    <p><a href="{{url('/')}}"><i class="fa fa-arrow-circle-left"></i> Back to Portfolio</a></p>
                    @if($owner)
                        <div title="Gallery Owner">
                            <img src="{{url('/')}}/images/profile/{{$owner->profile_pic}}" alt=""  class="owner-icon display-inline-block">
                            <h5 class="display-inline-block">{{$owner->name}}</h5>
                            <small class="roll">@if($owner->position == 1){{'@admin'}}@else{{'@Editor'}}@endif</small>
                        </div>
                    @endif
                    <h2>{{$gallery->name}}</h2>
                    <p class="lead">{{$gallery->description}}</p>
                    {{--                <p class="lead">{{ substr($gallery->description, 0,10)}}</p>--}}
{{--                    <a href="{{url('/')}}/photo/create/{{$gallery->id}}" class="button"><i class="	fa fa-upload"></i> Upload Portfolio</a><br><br>--}}
{{--                    <a href="{{url('/')}}/gallery/edit/{{$gallery->id}}"><button class="button bg-success"><i class="fa fa-edit"></i> Edit</button></a>--}}
{{--                    <a href="{{url('/')}}/gallery/destroy/{{$gallery->id}}" onclick="return confirm('Are you sure to delete?')"><button class="button bg-danger margin-0"><i class="fa fa-trash"></i> Delete</button></a>--}}
                </div>
                <div class="medium-5">
                    <div class="cell">
                        <a href="{{url('/')}}/images/{{$gallery->cover_image}}" target="_blank">
                            <img class="thumbnail cover-image-show" src="{{url('/')}}/images/{{$gallery->cover_image}}">
                        </a>
                    </div>
                </div>
            </div>
        </article>
    </div>
    <article class="grid-container">
        @if(count($photo))
        <div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-4">
            @foreach($photo as $p)
                <div class="cell">
                    <a href="{{url('/')}}/photo/details/{{$p->id}}">
                        <img class="thumbnail cover-image" src="{{url('/')}}/images/{{$p->image}}">
                    </a>
                    <h5>{{$p->title}}</h5>
                    <p class="text-justify">{{substr($p->description,0,75)}}......<a href="{{url('/')}}/photo/details/{{$p->id}}">More</a></p>
                </div>
            @endforeach
        </div>
        @else
            <div class="grid-x grid-margin-x small-up-12 medium-up-12 large-up-12">
                <div class="cell">
                    <h5 class="text-center display-block font-bold"> Not Found!</h5>
                </div>
            </div>
        @endif
        <hr>


    </article>
@stop

