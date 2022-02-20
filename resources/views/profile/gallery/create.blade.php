@extends('layouts/main')
@section('content')
    <div class="callout primary">
        <article class="grid-container">
            <div class="">
                <p><a href="{{url('/profile')}}/"><i class="fa fa-arrow-circle-left"></i> Back to Gallery</a></p>
                <h1>Create Portfolio Gallery</h1>
                <p class="lead">Create Portfolio Gallery and Start Upload Your Portfolio Image</p>
            </div>
        </article>
    </div>
    <article class="grid-container">
        <div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-4">
            <div class="maindiv">
                {!! Form::open(['action' => 'GalleryController@store','enctype'=>'multipart/form-data','method'=>'post'])!!}
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', $value = null,$attributes=['placeholder'=>'Gallery Name','name'=>'name','required'=>'required', 'class'=>'form-control']) !!}
                {!! Form::label('description', 'Description') !!}
                {!! Form::text('description', $value = null,$attributes=['placeholder'=>'Gallery Description','name'=>'description','required'=>'required', 'class'=>'form-control']) !!}
                {!! Form::label('cover_image', 'Cover Image') !!}
                {!! Form::file('cover_image') !!}
                {!! Form::submit('submit',$ttributes=['class'=>'button']) !!}
                {!! Form::close() !!}
            </div>
        </div>

        <hr>

    </article>
@stop
