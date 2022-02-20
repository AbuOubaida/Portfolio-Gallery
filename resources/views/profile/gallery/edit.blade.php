@extends('layouts/main')
@section('content')
    <div class="callout primary">
        <article class="grid-container">
            <div class="">
                <p><a href="{{url('/')}}/gallery/show/{{$gallery->id}}"><i class="fa fa-arrow-circle-left"></i> Back to Gallery</a></p>
                <h1>Edit Portfolio Gallery</h1>
                <p class="lead">Update/Edit Portfolio Gallery fo {{$gallery->name}}</p>
            </div>
        </article>
    </div>
    <article class="grid-container">
        <div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-4">
            <div class="maindiv">
                <form action="{{route('gallery.edit',$gallery->id)}}" enctype='multipart/form-data' method='post'>
                @csrf
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', $value = $gallery->name,$attributes=['placeholder'=>'Gallery Name','name'=>'name','required'=>'required', 'class'=>'form-control']) !!}
                {!! Form::label('description', 'Description') !!}
                {!! Form::textarea('description', $value = "$gallery->description",$attributes=['placeholder'=>'Portfolio Description','name'=>'description','required'=>'required', 'class'=>'form-control']) !!}
                {!! Form::label('cover_image', 'Cover Image') !!}
                {!! Form::file('cover_image',$attributes=['onChange'=>"displayImage(this)", 'id'=>"profileImage"])!!}
                    <div class="display-block width-100">
                        <img src="{{url('/')}}/images/{{$gallery->cover_image}}" alt="" width="20%" id="profileDisplay">
                    </div>
                    <input type="hidden" name="gallery_id" value="{{$gallery->id}}">
                    <br>
                {!! Form::submit('submit',$ttributes=['class'=>'button']) !!}
                </form>
            </div>
        </div>
        <hr>

    </article>
@stop
