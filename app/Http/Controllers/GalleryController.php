<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//use Illuminate\Auth;
use MongoDB\Driver\Session;
use App\Http\Controllers\HomeController;

class GalleryController extends Controller
{
    private $table = 'galleries';
    //List Gallery
    public function index()
    {
        //Get All Gallery Cover
        $gallery = DB::table($this->table)->where('status',1)->orderBy('id','desc')->get();

        //Render the view
       return view('gallery/index',compact('gallery'));
    }
    //show create form
    public function create()
    {
        $homeController = new HomeController();
        $homeController->currentPage();
        if(!Auth::check())
        {
            return redirect('login');
        }
        return view('profile/gallery/create');
    }
    //store gallery
    public function store(Request $request)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user = Auth::user();
        $name           = $request->input('name');
        $description    = $request->input('description');
        $cover_image    = $request->file('cover_image');
        $owner_id       = $user->id;
        //check Image Upload
        if ($cover_image)
        {
            $cover_image_file_name= $cover_image->getClientOriginalName();
            $cover_image->move(public_path('images'),$cover_image_file_name);
        }else{
            $cover_image_file_name = 'no_image.jpg';
        }

        // Insert Gallery Image
        DB::table($this->table)->insert(
            [
                'name'       => $name,
                'description'=> $description,
                'cover_image'=> $cover_image_file_name,
                'owner_id'   => $owner_id,
                'created_at' => date('YYYY-MM-DD hh:mm:ss')
            ]
        );
        // Set message
        \Session::flash('message','Gallery Created success');
        //Redirect
        return redirect('/profile');
    }
    //show gallery photo
    public function show($id)
    {
        $homeController = new HomeController();
        $homeController->currentPage();
        //Get Gallery
        $gallery = DB::table($this->table)->where('id',$id)->where('status',1)->first();
        //Get Photo
        $photo = DB::table('photos')->where('gallery_id',$id)->where('status',1)->orderBy('id','desc')->get();

        $owner_id = $gallery->owner_id ;
        $owner = DB::table('users')->select('name','profile_pic','position')->where('id',$owner_id)->where('status',1)->first();
        if ($gallery == null || $photo == null)
        {
            return redirect('/');
        }
        return view('gallery/show',compact('gallery','photo','owner'));
    }
    //Edit gallery
    public function edit($id)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user=Auth::user();
        $owner_id = $user->id;
        //Get Gallery
        $gallery = DB::table($this->table)->where('id',$id)->where('owner_id',$owner_id)->where('status',1)->first();
        if ($gallery == null)
        {
            return redirect('/');
        }
        return view('gallery/edit',compact('gallery'));
    }
    public function update(Request $request)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user=Auth::user();
        $owner_id = $user->id;
        $gallery_id = $request->input('gallery_id');
        $name       = $request->input('name');
        $description = $request->input('description');
        $cover_image = $request->file('cover_image');
        if ($cover_image != null)
        {
            if($cover_image->getMimeType() == 'image/png' || $cover_image->getMimeType() == 'image/PNG' || $cover_image->getMimeType() == 'image/jpg' || $cover_image->getMimeType() == 'image/jpeg')
            {
                $photo = DB::table($this->table)->where('id', $gallery_id)->where('status',1)->first();
                if (file_exists($filename='images/'.$photo->cover_image))
                {
                    unlink(public_path($filename));
                }
                $cover_image_file_name= $cover_image->getClientOriginalName();
                $cover_image->move(public_path('images'),$cover_image_file_name);
                DB::table($this->table)->where('id',$gallery_id)->where('owner_id',$owner_id)->where('status',1)->update(
                    [
                        'name'  => $name,
                        'description'   =>  $description,
                        'updated_at'    =>  date('YYYY-MM-DD hh:mm:ss'),
                        'cover_image'   =>  $cover_image_file_name
                    ]
                );
                //Sent Message
                \Session::flash('message','Gallery Update Successful');
                return redirect()->route('edit',array($gallery_id));
            }
            else{
                //Sent Message
                \Session::flash('error','Invalid Gallery Image Format');
                return redirect()->route('edit',array($gallery_id));
            }
        }
        else{
            DB::table($this->table)->where('id',$gallery_id)->where('status',1)->update(
                [
                    'name'  => $name,
                    'description'   =>  $description,
                    'updated_at'    =>  date('Y-m-d h:m:s')
                ]
            );

            //Sent Message
            \Session::flash('message','Gallery Update Successful');
            return redirect()->route('edit',array($gallery_id));
        }
    }
//    Delete Gallery
    public function destroy($gallery_id)
    {
        if(!Auth::check())
        {
            return redirect('login');
        }
        $user = Auth::user();
        $galleryUpdate = DB::table($this->table)->where('owner_id',$user->id)->where('id',$gallery_id)->update(['status'=>0]);
        $photoUpdate = DB::table('photos')->where('gallery_id',$gallery_id)->where('owner_id',$user->id)->update(['status'=>0]);
        if ($galleryUpdate && $photoUpdate)
        {
            \Session::flash('message','Gallery Delete Successfully');
            return redirect('profile');
        }
        else{
            \Session::flash('error','Gallery Delete not possible');
            return redirect()->back();
        }
    }
}
