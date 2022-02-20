@extends('layouts/main')
@section('content')
    <div class="callout primary">
        <article class="grid-container">
            <div class="">
                <p><a href="{{url('/profile')}}/gallery/show/{{$project->id}}"><i class="fa fa-arrow-circle-left"></i> Back to {{$project->name}} Project</a></p>
                <h1>Upload Portfolio</h1>
                <p class="lead">Upload the Portfolio photo to make gallery for your <span class="text-success">{{$project->name}}</span> project</p>
            </div>
        </article>
    </div>
    <article class="grid-container">
        <div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-4">
            <div class="maindiv">
                {!! Form::open(['action' => 'PhotoController@store','enctype'=>'multipart/form-data','method'=>'post'])!!}

                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', $value = null,$attributes=['placeholder'=>'portfolio Title','name'=>'title','required'=>'required', 'class'=>'form-control']) !!}

                {!! Form::label('description', 'Description') !!}
                {!! Form::text('description', $value = null,$attributes=['placeholder'=>'Portfolio Description','name'=>'description','required'=>'required', 'class'=>'form-control']) !!}

                {!! Form::label('location', 'Location') !!}
                {!! Form::text('location', $value = null,$attributes=['placeholder'=>'Portfolio Location','name'=>'location','required'=>'required', 'class'=>'form-control']) !!}

                {!! Form::label('image', 'Portfolio Image(.jpg/.jpeg/.png only)') !!}
                {!! Form::file('image') !!}

                <input type="hidden" name="gallery_id" value="{{$gallery_id}}">

                {!! Form::submit('submit',$attributes=['class'=>'button']) !!}
                {!! Form::close() !!}
            </div>
        </div>

        <hr>

    </article>
@stop
