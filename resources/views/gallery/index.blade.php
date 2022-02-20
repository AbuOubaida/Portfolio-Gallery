@extends('layouts/main')
@section('content')
    <div class="callout primary">
        <article class="grid-container">
            <div class="">
                <h1>Portfolio Gallery</h1>
                <p class="lead">Please Click My Portfolio Cover Image to see all Project</p>
{{--                <a href="{{url('/')}}/gallery/create/" class="button"><i class="fa fa-edit"></i> Create Gallery</a>--}}
            </div>
        </article>
    </div>
    <article class="grid-container">
        <div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-4">
            @foreach($gallery as $g)
            <div class="cell">
                <a href="gallery/show/{{$g->id}}">
                    <img class="thumbnail cover-image" src="images/{{$g->cover_image}}">
                </a>
                <h5>{{$g->name}}</h5>
                <p>{{substr($g->description,0,20)}}......<a href="{{url('/')}}/gallery/show/{{$g->id}}">More</a></p>
            </div>
            @endforeach

        </div>

        <hr>


    </article>
@stop
