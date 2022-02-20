<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HomeController;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\False_;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Collection;

class PhotoController extends Controller
{
    private $table = 'photos';
    private $table_gallery = 'galleries';
    //Show Create Form
    public function create($gallery_id)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user = Auth::user();
        $owner_id       = $user->id;
        $project = DB::table($this->table_gallery)->where('id',$gallery_id)->where('owner_id',$owner_id)->where('status',1)->first();
        if ($project == null)
        {
            return redirect('/');
        }
        return view('profile/photo/create',compact('gallery_id','project'));
    }
    //Store Photo
    public function store(Request $request)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user = Auth::user();
        $title          = $request->input('title');
        $description    = $request->input('description');
        $location       = $request->input('location');
        $gallery_id     = $request->input('gallery_id');
        $portfolio_img  = $request->file('image');
        $owner_id       = $user->id;
        if (!$gallery_id)
        {
            return redirect("profile");
        }
        else if (!$title || !$location)
        {
            //Sent Message
            \Session::flash('error','Empty field Error');
            return redirect("profile/photo/create/$gallery_id");
        }
        else if (!($portfolio_img->getMimeType() == 'image/png' || $portfolio_img->getMimeType() == 'image/jpg'|| $portfolio_img->getMimeType() == 'image/jpeg'))
        {
            //Sent Message
            \Session::flash('error','Invalid Image Format');
            return redirect("profile/photo/create/$gallery_id");
        }
        //check Image Upload
        else if ($portfolio_img != null && ($portfolio_img->getMimeType() == 'image/png' || $portfolio_img->getMimeType() == 'image/jpg'|| $portfolio_img->getMimeType() == 'image/jpeg'))
        {
            $portfolio_img_name = $title.'_'.$portfolio_img->getClientOriginalName();
            $portfolio_img->move(public_path('images'),$portfolio_img_name);
        }else{
            $portfolio_img_name = $title.'_no_image.jpg';
        }
        DB::table($this->table)->insert(
            [
                'title'      =>  $title,
                'description'=>  $description,
                'location'   =>  $location,
                'gallery_id' => $gallery_id,
                'owner_id'   =>  $owner_id,
                'image'      =>  $portfolio_img_name
            ]
        );
        //Sent Message
        \Session::flash('message','Portfolio Added Successfully');
        //Redirect
        return redirect("profile/gallery/show/$gallery_id");
//        return redirect()->route()
    }
    //Show Portfolio details
    public function details($id)
    {
        $homeController = new HomeController();
        $homeController->currentPage();
        $evolution_like = $homeController->evolutionStatus($id,'like');
        $evolution_dislike = $homeController->evolutionStatus($id,'dislike');
        $photo_comments = $this->getComment($id);
        $photo_comments = json_decode(json_encode($photo_comments),true);//Object to array convert with Std property
        $photo = DB::table($this->table)->where('id',$id)->where('status',1)->first();
        $owner_id = $photo->owner_id ;
        $owner = DB::table('users')->select('name','profile_pic','position')->where('id',$owner_id)->where('status',1)->first();
        if (Auth::check())
        {
            $owner_id = Auth::user()->id;
            $owner_like = $homeController->evolutionSingleStatus($id,$owner_id,'like');
            $owner_dislike = $homeController->evolutionSingleStatus($id,$owner_id,'dislike');
            return view('photo/details',compact('photo','evolution_like','evolution_dislike','owner_like','owner_dislike','photo_comments','owner'));
        }
        else{
            return view('photo/details',compact('photo','evolution_like','evolution_dislike','photo_comments','owner'));
        }
    }
    //Edit portfolio
    public function edit($id)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user=Auth::user();
        $owner_id = $user->id;
        $photo = DB::table($this->table)->where('id',$id)->where('owner_id',$owner_id)->where('status',1)->first();
        if ($photo == null)
        {
            return redirect('/');
        }
        return view('profile/photo/edit',compact('photo'));
    }
    //Delete portfolio
    public function destroy($id, $gallery_id)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user=Auth::user();
        $owner_id = $user->id;
//        $photo = DB::table($this->table)->where('id',$id)->where('gallery_id',$gallery_id)->where('owner_id',$owner_id)->delete();
        $photo = DB::table($this->table)->where('id',$id)->where('gallery_id',$gallery_id)->where('owner_id',$owner_id)->update(['status'=>0]);
        if ($photo == null)
        {
            return redirect('/');
        }
        //Sent Message
        \Session::flash('message','Portfolio Delete Successfully');
        //Redirect
        return redirect()->route('profileGalleryShow',array($gallery_id));
    }
    //Update photo
    public function update(Request $request)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user=Auth::user();
        $owner_id = $user->id;
        $photo_id     = $request->input('photo_id');
        $title          = $request->input('title');
        $description    = $request->input('description');
        $location       = $request->input('location');
        $portfolio_img  = $request->file('image');

        $photo = DB::table($this->table)->where('id',$photo_id)->where('owner_id',$owner_id)->where('status',1)->first();
        if ($photo == null)
        {
            return redirect('/');
        }
        if ($portfolio_img && !($portfolio_img->getMimeType() == 'image/png' || $portfolio_img->getMimeType() == 'image/jpg'|| $portfolio_img->getMimeType() == 'image/jpeg'))
        {
            //Sent Message
            \Session::flash('message','Invalid Image Format');
            return redirect("profile/photo/edit/$photo_id");
        }
        else if($portfolio_img != null && ($portfolio_img->getMimeType() == 'image/png' || $portfolio_img->getMimeType() == 'image/jpg'|| $portfolio_img->getMimeType() == 'image/jpeg'))
        {
            if (file_exists($name='images/'.$photo->image))
            {
                unlink(public_path($name));
            }
            $portfolio_img_name = $title.'_'.$portfolio_img->getClientOriginalName();
            $portfolio_img->move(public_path('images'),$portfolio_img_name);
            DB::table($this->table)->where('id',$photo->id)->where('owner_id',$owner_id)->where('status',1)->update(
            [
                'title'      =>  $title,
                'description'=>  $description,
                'location'   =>  $location,
                'image'      =>  $portfolio_img_name
            ]
            );
        }
        else{
            DB::table($this->table)->where('id',$photo->id)->where('owner_id',$owner_id)->where('status',1)->update(
                [
                    'title'      =>  $title,
                    'description'=>  $description,
                    'location'   =>  $location,
                ]
            );
        }
        //Sent Message
        \Session::flash('message','Portfolio Update Successfully');
        return redirect()->route('profilePhotoShow',array($photo_id));
    }
    public function evolution(Request $request, $id)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }

        $homeController = new HomeController();
        $table = 'photo_evolution';
//        $url = $homeController->currentPage();
        $evl = $request->post('value');// evl == 0 Dislike and evl = 1 like
        $photo_id   =   $id;
        $user_id    =   Auth::user()->id;
        $user_ip    =   $request->ip();
        $like_value = $homeController->evolutionSingleStatus($photo_id,$user_id,'like');
        $dislike_value = $homeController->evolutionSingleStatus($photo_id,$user_id,'dislike');
        $like_status = 0;
        $dislike_status = 0;
        config(['app.timezone' => 'Asia/Dhaka']);
        $like_date = null;
        $dislike_date = null;
        if ($like_value == 0 && $dislike_value == 0)
        {
            if ($evl == 1)
            {
//                like
                $like_status = 1;
                $dislike_status = 0;
                $like_date = date('Y-m-d H:i:s');
            }
            elseif ($evl == 0)
            {
//                dislike
                $like_status = 0;
                $dislike_status = 1;
                $dislike_date = date('Y-m-d H:i:s');
            }
            else{
                return false;
            }
            $evlSet = DB::table($table)->insert([
                'like_status'   =>  $like_status,
                'dislike_status'=>  $dislike_status,
                'photo_id'      =>  $photo_id,
                'user_id'       =>  $user_id,
                'user_ip'       =>  $user_ip,
                'like_date'     =>  $like_date,
                'dislike_date'  =>  $dislike_date
            ]);
            if ($evlSet)
            {
                $total_like = $homeController->evolutionStatus($photo_id,'like');
                $total_dislike = $homeController->evolutionStatus($photo_id,'dislike');
                $data = [];
                $data['like'] = $total_like;
                $data['dislike'] = $total_dislike;
                return $data;
            }
            else{
                return false;
            }
        }
        elseif ($evl == 1 && $dislike_value == 1)
        {
//           evl is dislike to like";
            $like_status = 1;
            $dislike_status = 0;
            $like_date = date('Y-m-d H:i:s');
            $evlUpdate = DB::table($table)->where('photo_id',$photo_id)->where('user_id',$user_id)->where('dislike_status',1)->update([
                'like_status'   =>  $like_status,
                'dislike_status'=>  $dislike_status,
                'user_ip'       =>  $user_ip,
                'like_date'     =>  $like_date,
                'dislike_date'  =>  null
            ]);
            if ($evlUpdate)
            {
                $total_like = $homeController->evolutionStatus($photo_id,'like');
                $total_dislike = $homeController->evolutionStatus($photo_id,'dislike');
                $data = [];
                $data['like'] = $total_like;
                $data['dislike'] = $total_dislike;
                $data['evl_status'] = 1;
                return $data;
            }
            else{
                return false;
            }
        }
        elseif ($evl == 0 && $like_value == 1)
        {
            //           evl is like to dislike";
            $like_status = 0;
            $dislike_status = 1;
            $dislike_date = date('Y-m-d H:i:s');
            $evlUpdate = DB::table($table)->where('photo_id',$id)->where('user_id',$user_id)->where('like_status',1)->update([
                'like_status'   =>  $like_status,
                'dislike_status'=>  $dislike_status,
                'user_ip'       =>  $user_ip,
                'like_date'     =>  null,
                'dislike_date'  =>  $dislike_date
            ]);
            if ($evlUpdate)
            {
                $total_like = $homeController->evolutionStatus($photo_id,'like');
                $total_dislike = $homeController->evolutionStatus($photo_id,'dislike');
                $data = [];
                $data['like'] = $total_like;
                $data['dislike'] = $total_dislike;
                $data['evl_status'] = 0;
                return $data;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    public function commentSet($id, Request $request)
    {
        if (!Auth::check())
        {
            return redirect('login');
        }
        $user_id = Auth::user()->id;
        $user_ip    =   $request->ip();
        $photo_id = $id;
        $comment = $request->post('comment');
        $table = 'photo_comment';
        $comment_at = date('Y-m-d H:i:s');
        if (empty($comment))
        {
            return false;
        }
        $commentSet = DB::table($table)->insert([
            'photo_id' => $photo_id,
            'commenter_id' => $user_id,
            'commenter_ip' => $user_ip,
            'comment_details' => $comment,
            'comment_status' => 1,
            'comment_at' => $comment_at
        ]);
        if ($commentSet)
        {
            $allComments = $this->getComment($id);
            return \response()->json($allComments);
        }
        else{
            return false;
        }
    }
    public function getComment($id)
    {
        if (empty($id))
        {
            return false;
        }
        $table = 'photo_comment';
        $getComment = DB::table($table)->join('photos',"$table.photo_id",'=','photos.id')->join('users',"$table.commenter_id",'=','users.id')->select("$table.*",'users.id as cmntr_id','users.name as cmntr_name','users.profile_pic as cmntr_pic')->where('photo_id',$id)->where('comment_status',1)->where('photos.status',1)->orderBy('comment_id','desc')->get();
        return $getComment;
    }
    public function deleteComment($id)
    {
        if (!Auth::check())
        {
            return redirect('login');
        }
        if ($id)
        {
            $user = Auth::user();
            $deleteComment = DB::table('photo_comment')->where('comment_id',$id)->where('commenter_id',$user->id)->update(['comment_status'=>0]);
            if ($deleteComment)
            {
                \Session::flash('message','Comment Delete Successful');
                return redirect()->back();
            }
            else{
                \Session::flash('message','Comment Delete not possible');
                return redirect()->back();
            }
        }
        else{
            return redirect()->back();
        }
    }
}
