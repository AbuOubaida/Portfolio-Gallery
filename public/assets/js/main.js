function triggerClick(e) {
    document.querySelector('#profileImage').click();
}
function displayImage(e) {
    if (e.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e){
            document.querySelector('#profileDisplay').setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(e.files[0]);
    }
}
function displayProfileImage(e) {
    if (e.files[0]) {
        let reader = new FileReader()
        reader.onload = function (e) {
            $('#editProfilePic').attr('src',e.target.result)
            $('#profileDisplay').attr('src',e.target.result)
            $('.profile-pic').attr('src',e.target.result)
        }
        reader.readAsDataURL(e.files[0])
    }
}
function displayCoverImage(e) {
    if (e.files[0]) {
        let reader = new FileReader()
        reader.onload = function (e) {
            $('#editCoverImg').attr('style',"background-image: linear-gradient(rgba(0, 0, 255, 0.5), rgba(255, 255, 0, 0.5)), url("+ e.target.result+")")
            $('#coverDisplay').attr('src',e.target.result)
        }
        reader.readAsDataURL(e.files[0])
    }
}
function clickAjax(e) {
    let id = $(e).attr('id')
    let photo = $(e).attr('take')
    let value = null
    let likeCount = $('#like-count')
    let dislikeCount = $('#dislike-count')
    if (id === 'like')value = 1
    else if (id === 'dislike')value = 0
    else value = null
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:"evolution/"+photo,
        type: "POST",
        data:{"value": value},
        success:function (data) {
            if (data === false)
            {
                return false
            }
            likeCount.html(data['like'])
            dislikeCount.html(data['dislike'])
            if (value == 1)
            {
                $('#like').removeAttr('onclick')
                $('#like').attr('status',1)
                $('.like').addClass('like_active')
                $('#like-mark').addClass('like_active')
                $('#like-mark').html('Liked')
                if (data['evl_status'] === 1)
                {
                    // dislike to like update
                    $('.dislike').removeClass('dislike_active')
                    $('#dislike-mark').removeClass('dislike_active')
                    $('#dislike-mark').html('Dislike')
                    $('#dislike').attr('onclick','clickAjax(this)')
                    $('#dislike').attr('status',0)
                }
            }
            else if (value == 0)
            {
                $('#dislike').removeAttr('onclick')
                $('#dislike').attr('status',1)
                $('.dislike').addClass('dislike_active')
                $('#dislike-mark').addClass('dislike_active')
                $('#dislike-mark').html('Disliked')
                if (data['evl_status'] === 0)
                {
                    // like to dislike update
                    $('.like').removeClass('like_active')
                    $('#like-mark').removeClass('like_active')
                    $('#like-mark').html('Like')
                    $('#like').attr('onclick','clickAjax(this)')
                    $('#like').attr('status',0)
                }
            }
            else {
                return false
            }
            if (data['evl_status'] === 1 && value == 1)
            {
                // dislike to like update
                $('.dislike').removeClass('dislike_active')
                $('#dislike-mark').removeClass('dislike_active')
                $('#dislike-mark').html('Dislike')
                $('#dislike').attr('onclick','clickAjax(this)')
                $('#dislike').attr('status',0)
            }
            else if (data['evl_status'] === 0 && value == 0)
            {
                // like to dislike update
                $('.like').removeClass('like_active')
                $('#like-mark').removeClass('like_active')
                $('#like-mark').html('Like')
                $('#like').attr('onclick','clickAjax(this)')
                $('#like').attr('status',0)
            }
            else {
                return false
            }
        }

    })
}
function ClickCommentSet(e) {
    let comment = $('#comment').val()
    let photo = $(e).attr('take')
    let rootURL = window.location.origin
    if (comment.length === 0)
    {
        return false
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:"comment/"+photo,
        type: "POST",
        data:{"comment": comment},
        success:function (data) {
            if (data === false)
            {
                return false
            }
            $('#count-comment').html('('+data.length+')')
            $('#comment_section').html('')
            $('#comment').val('')
            $.each(data,function (k,v) {
                $('#comment_section').append(
                    "<div class='text-justify comment-bg'>\n" +
                    "<h6> <span class=''><img class='commenter-icon' src='"+rootURL+"/my-app/public/images/profile/"+ v['cmntr_pic']+"'></span> "+v['cmntr_name']+"</h6>\n" +"<p>"+v['comment_details']+"</p>\n" + "" +
                    "</div>")
            })
        }
    })
}
