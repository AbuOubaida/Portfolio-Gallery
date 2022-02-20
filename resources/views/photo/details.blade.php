@extends('layouts/main')
@section('content')
    @if($photo)
    <div class="callout primary">
        <article class="grid-container">
            <div class="">
                <p><a href="{{url('/')}}/gallery/show/{{$photo->gallery_id}}"><i class="fa fa-arrow-circle-left"></i> Back to Portfolio Gallery</a></p>
                <h1>Portfolio Details</h1>
{{--                <a href="{{url('/')}}/photo/create/{{$photo->gallery_id}}" class="button"><i class="	fa fa-upload"></i> Upload Portfolio</a>--}}
            </div>
        </article>
    </div>
    <?php
    $user = \Illuminate\Support\Facades\Auth::user();
    ?>
    <article class="grid-container">
        <div class="grid-x grid-margin-x small-up-1 medium-up-1 large-up-1">
            <div class="cell">
                <div class="callout">
                    <h4>{{$photo->title}}</h4>
                    @if($owner)
                    <div title="Portfolio Owner">
                        <img src="{{url('/')}}/images/profile/{{$owner->profile_pic}}" alt=""  class="owner-icon display-inline-block">
                        <h5 class="display-inline-block">{{$owner->name}}</h5>
                        <small class="roll">@if($owner->position == 1){{'@admin'}}@else{{'@Editor'}}@endif</small>
                    </div>
                    @endif
                    <h5><b ><i class='fas fa-map-marker-alt' style='font-size:20px;color:red'></i></b> {{$photo->location}}</h5>
                    <p class="text-justify">{{$photo->description}}.</p>
                    <a href="{{url('/')}}/images/{{$photo->image}}" target="_blank">
                        <img class="img-responsive" src="{{url('/')}}/images/{{$photo->image}}">
                    </a>
                </div>
            </div>
        </div>
{{--            Comment and Evolution  Section--}}
        <div class="">
            <div class="grid-x grid-margin-x small-up-2 medium-up-2 large-up-2">
                <div class="cell evl">
                    @if(\Illuminate\Support\Facades\Auth::check())

                        {{--                    <a href="{{url('/')}}/photo/edit/{{$photo->id}}"><button class="button"><i class="fa fa-thumbs-up"></i> Edit</button></a>--}}
                        <span>
                        <a @if(\Illuminate\Support\Facades\Auth::check() && $owner_like<=0) {{'onclick=clickAjax(this)'}} @endif id="like" take="{{$photo->id}}" status="@if(\Illuminate\Support\Facades\Auth::check()){{$owner_like}}@endif"><button class="like @if(\Illuminate\Support\Facades\Auth::check() && $owner_like>=1) {{'like_active'}} @endif"><i class="fa fa-thumbs-up"></i></button></a>
                        @endif
                        <span class="evl-text @if(\Illuminate\Support\Facades\Auth::check() && $owner_like>=1) {{'like_active'}} @endif" id="like-mark">@if(\Illuminate\Support\Facades\Auth::check() && $owner_like>=1) {{'Liked'}}@else{{"Like"}} @endif</span>
                        <span id="like-count">{{$evolution_like}}</span>&nbsp;&nbsp;
                    </span>
                        @if(\Illuminate\Support\Facades\Auth::check())
                            <span>
                        <a @if(\Illuminate\Support\Facades\Auth::check() && $owner_dislike<=0) {{"onclick=clickAjax(this)"}} @endif id="dislike" take="{{$photo->id}}" status="@if(\Illuminate\Support\Facades\Auth::check()){{$owner_dislike}}@endif"><button class="dislike @if(\Illuminate\Support\Facades\Auth::check() && $owner_dislike>=1) {{'dislike_active'}} @endif"><i class="fa fa-thumbs-down"></i> </button></a>
                        @endif
                        <span class="evl-text @if(\Illuminate\Support\Facades\Auth::check() && $owner_dislike>=1) {{'dislike_active'}} @endif" id="dislike-mark">@if(\Illuminate\Support\Facades\Auth::check() && $owner_dislike>=1) {{'Disliked'}}@else{{"Dislike"}} @endif</span>
                        <span id="dislike-count">{{$evolution_dislike}}</span>
                    </span>

                            {{--                    <a href="{{url('/')}}/photo/destroy/{{$photo->id}}/{{$photo->gallery_id}}" onclick="return confirm('Are you sure to delete?')"><button class="button bg-danger margin-0"><i class="fa fa-trash"></i> Delete</button></a>--}}
                </div>
                <div class="cell text-right">
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <input placeholder="write your comments here" name="comment" required="required" class="form-control comment-input" type="text" id="comment">
                        <input take="{{$photo->id}}" name="title"  class="form-control comment-btn" type="submit" value="Comment" onclick="ClickCommentSet(this)">
                    @else
                        Write comment to <a href="{{url('/')}}/login">Login</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="grid-x grid-margin-x small-up-1 medium-up-1 large-up-1" id="comment-section">
            <div class="cell">
                <div class="callout">
                    <h4>All Comment About {{$photo->title}}</h4>
                    <div class="d-block"><a href="#comment-section" class="" style="margin-top: 5px;"><u>View all comments</u> <span id="count-comment">({{count($photo_comments)}})</span></a></div>
                    <div id="comment_section">
                        @foreach($photo_comments as $photo_comment)
                        <div class="text-justify comment-bg">
                            @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->id == $photo_comment['commenter_id'])
                            <small class="float-right comment-op-icon" title="delete"><a href="delete-comment/{{$photo_comment['comment_id']}}" onclick="return confirm('Are you sure delete this comment?')" class="text-danger"><i class="fa fa-trash"></i> </a></small>
                            @endif
                            <h6> <img src="{{url('/')}}/images/profile/{{$photo_comment['cmntr_pic']}}" class="commenter-icon"> {{$photo_comment['cmntr_name']}}</h6>
                            <p>{{$photo_comment['comment_details']}}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </article>
    @else
        <div class="grid-x grid-margin-x small-up-12 medium-up-12 large-up-12">
            <div class="cell">
                <h5 class="text-center display-block font-bold"> Not Found!</h5>
            </div>
        </div>
    @endif
@stop

