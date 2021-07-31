<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Channel;
use App\Model\Media;
use App\Model\Playlist;
use App\Model\Subscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function register(Request $request){
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required|confirmed',
            'dob' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'user_type' => 'required|string|max:255',
        ]);

        $user = User::create([
            'firstname' => $validatedData['firstname'],
            'lastname' => $validatedData['lastname'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'user_type' => $validatedData['user_type'],
            'dob' => $validatedData['dob'],
            'gender' => $validatedData['gender'],
            'country' => $validatedData['country'],
            'state' => $validatedData['state'],
        ]);

        if($user == null){
            return $this->redirectPage("register", "Error Registering User");
        }

        if($user->user_type == "artist"){
            $channel = new Channel();
            $channel->title = $validatedData['firstname'] ."_". $validatedData['lastname'];
            $channel->user_id = $user->id;
            $channel->active = 0;
            $channel->save();
        } else if($user->user_type == "listener"){
            $playlist = new Playlist();
            $playlist->title = $user->firstname ."_".$user->lastname;
            $playlist->user_id = $user->id;
            $playlist->medias = json_encode([]);
            $playlist->save();

            $subcription = new Subscription();
            $subcription->user_id = $user->id;
            $subcription->isSubscribed = "false";
            $resp = $subcription->save();
            if($resp == false){
                return $this->redirectPage("register", "Error Registering User");
            }
        }
        
        return redirect('/viewer_login');
    }

    public function redirectPage($view, $err_msg = null){
        if($err_msg == null){
            return view($view);
        }
        return view($view, ['err_msg' => $err_msg]);
    }

    public function login(Request $request){
        
        $login = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $login['email'];
        $user_type = User::select('user_type')->where('email', $email)->first();
        //dd($user_type);
        if(isset($user_type)){
            if($user_type->user_type == "artist"){

                if(Auth::guard('artist')->attempt($login)){
                    return redirect('/artist/index');
                } else{
                    return $this->loginRedirect("Invalid Login Details");
                }
    
            } else if($user_type->user_type == "listener"){
    
                if(Auth::guard('listener')->attempt($login)){
                    $this->checkSubscription($login['email']);
                    return redirect('/listener/index');
                }else{
                    return $this->loginRedirect("Invalid Login Details");
                }
    
            }
        }else{

            return $this->loginRedirect("No user registered with this email account");

        }
        
        

    }

    public function loginRedirect($err_msg){
        return view('login',['err_msg' => $err_msg]);
    }

    public function logout(){

        Auth::logout();
        return redirect('/viewer_login');
    }

    public function index($videos = null){
        
        $videos = Media::all();
        return view('viewer.index', ['videos' => $videos]);
        
    }

    public function viewMedia($id){

        $media = Media::findOrFail($id);
        $vid_tags = explode(",", $media->tags);
        $views = json_decode($media->views);
        //dd($views);
        $user_id = Auth::id();
        if(!in_array($user_id,$views)){
            array_push($views,$user_id);
            $media->views = json_encode($views);
            $media->save();
        }

        $num_views = count($views);

        if($num_views == 0 || $num_views == 1){
            $num_views = $num_views ." view";
        } else{
            $num_views = $num_views ." views";
        }
        
        //$vid = $this->displayImage($media->filename);
        //dd($vid);
        return view("viewer.video-page", ['media' => $media, 'vid_tags' => $vid_tags, 'num_views' => $num_views]);
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
            return view('viewer.index', ['videos' => $videos]);
        }else{
            return view('viewer.index');
        }
    }

    public function checkSubscription($email){
        $user = User::where('email',$email)->first();
        if($user == null){
            return;
        }
        $today = Carbon::now();
        if($user != null && $user->subscription->expiry_date != null){
            if($user->subscription->expiry_date->lte($today)){
                $subscription = Subscription::where('user_id',$user->id)->first();
                //dd($user->subscription->expiry_date);
                if($subscription != null){
                    $subscription->isSubscribed = "false";
                    $subscription->save();
                }   
            }
        }
    }

    
}
