<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MongoDB\Driver\Session;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\HomeController;
use Illuminate\Testing\Fluent\Concerns\Has;

class ProfileController extends Controller
{
    private $galleries = 'galleries';
    private $photos = 'photos';
    //This is Index
    public function index()
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user = Auth::user();
        $owner_id = $user->id;
        $gallery = DB::table($this->galleries)->where('owner_id',$owner_id)->where('status',1)->get();
        return view('profile/gallery/index',compact('gallery'));
    }
//  Edit Profile
    public function edit()
    {
        if (!Auth::check())
        {
            return redirect('login');
        }
        $user = json_decode(json_encode(Auth::user()));
        return view('profile/edit',compact('user'));

    }
//    Update Profile
    public function update(Request $request)
    {
        if (!Auth::check())
        {
            return redirect('login');
        }
        $user = Auth::user();
        $id = $user->id;
        $name = $request->input('name');
        $profile_image = $request->file('profile_pic');
        $cover_image = $request->file('cover_pic');
        $request->validate([
            'name'=>'required|max:100',
            'profile_pic'=>'mimes:jpg,jpeg,png|max:1024',
            'cover_image'=>'mimes:jpg,jpeg,png|max:1536'
        ]);
//        Image Check empty or not
        if ($profile_image == null)
        {
            $profileImageName = $user->profile_pic;
        }
        else{
            if (file_exists($filename='images/profile/'.$user->profile_pic) && $user->profile_pic != 'git.png')
            {
                unlink(public_path($filename));
            }
            $profileImageName = $user->id.'_'.$profile_image->getClientOriginalName();
            $profile_image->move(public_path('images/profile/'),$profileImageName);
        }
        if ($cover_image == null)
        {
            $coverImageName = $user->cover_image;
        }
        else{
            if ($cover_image != null && file_exists($filename='images/cover_profile/'.$user->cover_image) && $user->cover_image != 'cover2.png')
            {
                unlink(public_path($filename));
            }
            $coverImageName = $user->id.'_'.$cover_image->getClientOriginalName();
            $cover_image->move(public_path('images/cover_profile/'),$coverImageName);
        }
        $update = DB::table('users')->where('id',$id)->where('status',1)->update([
            'name'=>$name,
            'profile_pic'=>$profileImageName,
            'cover_image'=>$coverImageName
        ]);
        if ($update)
        {
            //Sent Message
            \Session::flash('message','Profile Update Successful');
            return redirect('profile/edit/'.$id);
        }
        else{
            //Sent Message
            \Session::flash('error','Profile Update Not Possible');
            return redirect('profile/edit/'.$id);
        }

    }
    //show gallery photo
    public function show($id)
    {
        $user=Auth::user();
        $owner_id = $user->id;
        //Get Gallery
        $gallery = DB::table($this->galleries)->where('id',$id)->where('owner_id',$owner_id)->where('status',1)->first();
        if ($gallery == null)
        {
            return redirect('/');
        }
        //Get Photo
        $photo = DB::table($this->photos)->where('gallery_id',$id)->where('owner_id',$owner_id)->where('status',1)->get();
        if ($photo == null)
        {
            return redirect('/');
        }
        $owner_id = $gallery->owner_id ;
        $owner = DB::table('users')->select('name','profile_pic','position')->where('id',$owner_id)->where('status',1)->first();
        return view('profile/gallery/show',compact('gallery','photo','owner'));
    }
    //Show Portfolio details
    public function PhotoDetails($id)
    {
        $homeController = new HomeController();
        $photoController = new PhotoController();
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user=Auth::user();
        $owner_id = $user->id;
        $evolution_like = $homeController->evolutionStatus($id,'like');
        $evolution_dislike = $homeController->evolutionStatus($id,'dislike');
        $photo_comments = $photoController->getComment($id);
        $photo_comments = json_decode(json_encode($photo_comments),true);//Object to array convert with Std property
        $photo = DB::table($this->photos)->where('id',$id)->where('owner_id',$owner_id)->where('status',1)->first();
        $owner_id = $photo->owner_id ;
        $owner = DB::table('users')->select('name','profile_pic','position')->where('id',$owner_id)->where('status',1)->first();
        if ($photo == null)
        {
            return redirect('/');
        }
        if (Auth::check())
        {
            $owner_id = Auth::user()->id;
            $owner_like = $homeController->evolutionSingleStatus($id,$owner_id,'like');
            $owner_dislike = $homeController->evolutionSingleStatus($id,$owner_id,'dislike');
            return view('profile/photo/details',compact('photo','evolution_like','evolution_dislike','owner_like','owner_dislike','photo_comments','owner'));
        }
//        return view('profile/photo/details',compact('photo','evolution_like','evolution_dislike'));
    }
    public function changePassword()
    {
        if (!Auth::check())
        {
            return redirect('login');
        }
        return view('profile/changePassword');
    }
    public function updatePassword(Request $request)
    {
        if (!Auth::check())
        {
            return redirect('login');
        }
        $request->validate([
            'oldPassword'=>'required|min:6|max:100',
            'newPassword'=>'required|min:6|max:100',
            'conPassword'=>'required|min:6|max:100|same:newPassword'
        ]);
        $user = Auth::user();
        if (Hash::check($request->oldPassword,$user->password))
        {
            $update = DB::table('users')->where('id',$user->id)->where('status',1)->update([
                'password'=>bcrypt($request->conPassword)
            ]);
            if ($update)
            {
                //Sent Message
                \Session::flash('message','Password Change Successful');
                return redirect()->back();
            }
            else{
                //Sent Message
                \Session::flash('error','Password Change not possible');
                return redirect()->back();
            }
        }
        else{
            //Sent Message
            \Session::flash('error','Old Password dose not match');
            return redirect()->back();
        }

    }

}
