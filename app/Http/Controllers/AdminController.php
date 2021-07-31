<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Channel;
use App\Model\Media;
use App\Model\Advert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Intervention\Image\ImageManager;


class AdminController extends Controller
{
    public function register(Request $request){
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'firstname' => $validatedData['firstname'],
            'lastname' => $validatedData['lastname'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'user_type' => 'admin',
        ]);
        
        // $channel = new Channel();
        // $channel->title = $validatedData['firstname'] ."_". $validatedData['lastname'];
        // $channel->user_id = $user->id;
        // $channel->active = 1;

        // $resp = $channel->save();

        if($user != null){
           return redirect('/admin_login');
        } else{
            return $this->redirectPage("admin.register","Error registering Account");
        }
    }

    public function login(Request $request){
        
        $login = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $login['email'];
        $password = $login['password'];

        if(Auth::guard('admin')->attempt(['email' => $email, 'password' => $password, 'user_type' => 'admin'])){
            return redirect('/admin/index');
        } else{
            $err_msg = "Invalid login Details";
            return view('admin.login', ['err_msg' => $err_msg]);
        }

    }

    public function redirectPage($view, $err_msg = null){
        if($err_msg == null){
            return view($view);
        }
        return view($view, ['err_msg' => $err_msg]);
    }

    public function logout(){

        Auth::logout();
        return redirect('/admin_login');
    }

    public function allAds(){
        $adverts = Advert::orderBy('created_at', 'desc')->get();
        if($adverts == null || count($adverts) == 0){
            return view('admin.allAds');
        }
        return view('admin.allAds', ['adverts' => $adverts]);
    }

    public function index(){
        $users = User::orderBy('created_at', 'desc')->where('user_type', '<>', 'admin')->get();
        if($users == null || count($users) == 0){
            return view('admin.index');
        }
        return view('admin.index', ['users' => $users]);
    }

    public function redirectToSetUploadVideoView( $err_msg = null){
        return view('admin.upload-video', [ 'err_msg' => $err_msg]);
    }

    public function UploadAdvert(Request $request){

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'tags' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'content' => 'required',
        ]);     


        $extension = $request->file('content')->getClientOriginalExtension();
        

        if(!$this->validateContentExtension($extension)){

            $err_msg = "Unsupported File Format";
            return $this->redirectPage("admin.upload-advert",$err_msg);

        }
        

        $type = $this->checkExtension($extension);
        //dd($type);

        if($type == false){
            $err_msg = "Unsupported File Format";
            return $this->redirectPage("admin.upload-advert",$err_msg);
        }
        
        if(strcmp($type, "image") == 0){
            $path = $request->file('content')->store('images');
            //dd($path."bb");

            $manager = new ImageManager();
            $image = $manager->make('storage/'.$path)->resize(335, 108);
            $image->save('storage/'.$path);
        } else if(strcmp($type, "audio") == 0){
            $path = $request->file('content')->store('audios');
            //dd($path);
        } else {
            $err_msg = "Unsupported File Format";
            return $this->redirectPage("admin.upload-advert",$err_msg);
        }
        //dd($path);

        

        $advert = Advert::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'tags' => $data['tags'],
            'owner' => $data['owner'],
            'type' => $type,
            'active' => 1,
            'content' => $path,
        ]);

        return redirect('/admin/ads');
    }

    public function viewMedia($id){
        $media = Media::findOrFail($id);
        $vid_tags = explode(",", $media->tags);
        $views = json_decode($media->views);
        //dd($views);
        $num_views = count($views);

        if($num_views == 0 || $num_views == 1){
            $num_views = $num_views ." view";
        } else{
            $num_views = $num_views ." views";
        }
        return view("admin.video-page", ['media' => $media, 'vid_tags' => $vid_tags, 'num_views' => $num_views]);
    }

    public function checkExtension($ext){
        $audio_extensions = array("mp3","ogg", "wav");
        $img_extensions = array("jpeg", "gif", "png", "apng", "svg", "bmp", "ico", "jpg");
        foreach($audio_extensions as $extension){
            if(strcmp($extension,strtolower($ext)) == 0){
                return "audio";
            }
        }
        foreach($img_extensions as $extension){
            if(strcmp($extension,strtolower($ext)) == 0){
                return "image";
            }
        }                  
        return false;
    }

    public function validateContentExtension($ext){
        $extensions = array("jpeg", "gif", "png", "apng", "jpg", "svg", "bmp", "ico","mp3","ogg", "wav");
        foreach($extensions as $extension){
            if(strcmp($extension,strtolower($ext)) == 0){
                return true;
            }
        }                  
        return false;
    }

    public function searchVideo(Request $request){
        $data = $request->validate([
            'search' => 'required|string',
        ]);
        $searchVal = $data['search'];
        $videos = Media::where('title','LIKE','%'.$searchVal.'%')->get();
        //dd($videos);
        if(count($videos) > 0){
            return $this->renderSearch($videos);
        }else{
            return $this->renderSearch();
        }
    }

    public function renderSearch($videos = null){
        if(isset($videos)){
            return view('admin.index', ['videos' => $videos]);
        }else{
            return view('admin.index');
        }
    }

    public function activateAd($id){
        $advert = Advert::findOrFail($id);
        if($advert == null){
            return $this->index();
        }

        $advert->active = 1;
        $advert->save();
        return $this->allAds();
        
    }


    public function disableAd($id){
        $advert = Advert::findOrFail($id);
        if($advert == null){
            return $this->index();
        }

        $advert->active = 0;
        $advert->save();
            return $this->allAds();
    }

    public function allRoyalties(){
        $medias = Media::where('royalties','>',0)->get();

        if(count($medias) == 0){
            return view("admin.royalties");
        }

        return view("admin.royalties", ['medias' => $medias]);
    }

    public function confirmPay($id){
        $media = Media::findOrFail($id);
        if($media != null){
            $media->royalties = 0;
            $media->save();
            return $this->allRoyalties();
        }
    }

    public function deleteUser($id){
        $user = User::findOrFail($id);

        if($user == null){
            return $this->index();
        }

        $user->delete();
        return $this->index();
    }


    public function deleteAd($id){
        $advert = Advert::findOrFail($id);

        if($advert == null){
            return $this->allAds();
        }

        $advert->delete();
        return $this->allAds();
    }


    
}
