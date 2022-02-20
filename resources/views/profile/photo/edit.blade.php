@extends('layouts/main')
@section('content')
    <div class="callout primary">
        <article class="grid-container">
            <div class="">
                <p><a href="{{url('/')}}/profile/photo/details/{{$photo->id}}"><i class="fa fa-arrow-circle-left"></i> Back to Portfolio Details</a></p>
                <h1>Edit Portfolio Here</h1>
                <p class="lead">Upload the Portfolio photo to make gallery for your {{$photo->title}} project</p>
            </div>
        </article>
    </div>
    <article class="grid-container">
        <div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-4">
            <div class="maindiv">
                {!! Form::open(['action' => 'PhotoController@update','enctype'=>'multipart/form-data','method'=>'post'])!!}
                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', $value = "$photo->title",$attributes=['placeholder'=>'portfolio Title','name'=>'title','required'=>'required', 'class'=>'form-control']) !!}

                {!! Form::label('description', 'Description') !!}
                {!! Form::textarea('description', $value = "$photo->description",$attributes=['placeholder'=>'Portfolio Description','name'=>'description','required'=>'required', 'class'=>'form-control']) !!}

                {!! Form::label('location', 'Location') !!}
                {!! Form::text('location', $value = "$photo->location",$attributes=['placeholder'=>'Portfolio Location','name'=>'location','required'=>'required', 'class'=>'form-control']) !!}

                {!! Form::label('image', 'Portfolio Image') !!}
                {!! Form::file('image',$attributes=['onChange'=>"displayImage(this)", 'id'=>"profileImage"]) !!}
                <div class="display-block width-100">
                    <img src="{{url('/')}}/images/{{$photo->image}}" alt="" width="20%" id="profileDisplay">
                </div>
                <input type="hidden" name="photo_id" value="{{$photo->id}}">

                {!! Form::submit('Update',$attributes=['class'=>'button']) !!}
                {!! Form::close() !!}
            </div>
        </div>

        <hr>

    </article>
@stop
