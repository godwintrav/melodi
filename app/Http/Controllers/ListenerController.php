<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Channel;
use App\Model\Media;
use App\Model\Advert;
use App\Model\Subscription;
use App\Model\Playlist;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class ListenerController extends Controller
{
    private $searchVal;
    public function index($medias = null){
        
        $medias = Media::orderBy('created_at', 'desc')->get();
        $channels = Channel::orderBy('created_at', 'desc')->get();
        if($medias == null || count($medias) == 0){
            return view('listener.index');
        }
        
        return view('listener.index',['medias' => $medias, 'channels' => $channels]);
    }

    public function logout(){

        Auth::logout();
        return redirect('/viewer_login');
    }

    public function viewMedia($id){  
        if(!$this->checkSubscription()){
            return redirect('/listener/payment/'.$id);
        }
        $media = Media::findOrFail($id);
        $media->royalties += 0.001;
        if($media == null){
            return $this->redirectPage("listener.index", "Error getting video");
        }
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

        $adverts = Advert::orderBy('created_at', 'desc')
                                    ->where("type","image")
                                    ->where("active",1)
                                    ->limit(2)
                                    ->get();
        $upNexts = Media::orderBy('created_at', 'desc')
                                    ->where('id','<>',$media->id)
                                    ->inRandomOrder()
                                    ->limit(6)
                                    ->get();

        
        return view("listener.video-page", ['media' => $media, 'vid_tags' => $vid_tags, 'num_views' => $num_views, 'adverts' => $adverts, 'upNexts' => $upNexts]);
    }

    public function redirectPage($view, $err_msg = null){
        if($err_msg == null){
            return view($view);
        }
        return view($view, ['err_msg' => $err_msg]);
    }

    public function viewChannel($id){
        $channel = Channel::findOrFail($id);
        if($channel == null){
            return $this->redirectPage("listener.index", "Error getting video");
        }
        //dd($channel->medias());
        return view('listener.single-channel', ['channel' => $channel]);
    }

    public function allChannels(){
        $channels = Channel::orderBy('created_at', 'desc')->get();
        $featured_songs = Media::orderBy('created_at', 'desc')->limit(2)->get();
        if($channels == null || count($channels) == 0){
            return $this->redirectPage("listener.channels","No Channel Created");
        }
        return view('listener.channels', ['channels' => $channels, 'featured_songs' => $featured_songs]);
    }

    public function loadAdPage($id){
        $media = Media::findOrFail($id);
        $audio_advert = Advert::where("type","audio")
                        ->where("active",1)
                        ->orWhere("tags",'LIKE', '%'.$media->tags.'%') 
                        ->limit(1)
                        ->get();
        $img_adverts = Advert::where("type","image")
                        ->where("active",1)
                        ->orWhere("tags",'LIKE', '%'.$media->tags.'%')
                        ->limit(1)
                        ->get();
        $upNexts = Media::orderBy('created_at', 'desc')
                        ->where('id','<>',$media->id)
                        ->inRandomOrder()
                        ->limit(6)
                        ->get();
        if(count($audio_advert) == 0){
            return $this->viewMedia($id);
        } else{
            //dd($audio_advert);
            return view("listener.ad-page", ['audio_advert' => $audio_advert, 'img_adverts' => $img_adverts, 'id' => $id,'upNexts' => $upNexts]);
            
        }
    }

    public function searchVideo(Request $request){
        $data = $request->validate([
            'search' => 'required|string',
        ]);
        $this->searchVal = $data['search'];
        $medias = Media::orderBy('created_at', 'desc')
                    ->where(function($query){
                        $query->where("tags",'LIKE', '%'.$this->searchVal.'%')
                        ->orWhere("title",'LIKE', '%'.$this->searchVal.'%')
                        ->orWhere("description",'LIKE', '%'.$this->searchVal.'%');
                    })
                    ->limit(6)
                    ->get();
        //dd($medias);
        if(count($medias) > 0){
            return $this->renderSearch($this->searchVal, $medias);
        }else{
            return $this->renderSearch($this->searchVal);
        }
    }

    public function renderSearch($input, $medias = null){

        $channels = Channel::orderBy('created_at', 'desc')->get();

        if(isset($medias)){
            return view('listener.index', ['medias' => $medias, 'input' => $input, 'channels' => $channels]);
        }else{
            return view('listener.index',['input' => $input, 'channels' => $channels]);
        }
    }

    public function addToPlaylist($id){
        $media = Media::find($id);
        $playlist = Playlist::where('user_id',Auth::id())->first();
        if($media == null || $playlist == null){
            echo json_encode("Error Adding song");
            exit;
        }
        $medias = json_decode($playlist->medias);
        if(!in_array($media->id,$medias)){
            $media_id = intval($media->id);
            array_push($medias, $media_id);
            $playlist->medias = json_encode($medias);
            $playlist->save();
            echo json_encode("Song Added To Playlist");
            exit;
        }

        echo json_encode("Song Exists in Playlist");
        exit;

    }

    public function user_playlist(){
        $playlist = Playlist::where('user_id',Auth::id())->first();
        $media_ids = json_decode($playlist->medias);
        $medias = array();
        foreach($media_ids as $media_id){
            $media = Media::findOrFail($media_id);
            array_push($medias, $media);
        }

        if($medias == null || count($medias) == 0){
            return $this->redirectPage('listener.playlist', "No Song Added To Playlist");
        }

        return view('listener.playlist', ['medias' => $medias]);

    }

    public function paymentPage($id){
        return view('listener.payment', ['media_id' => $id]);
    }

    public function confirmPayment(Request $request){

        Stripe::setApiKey('sk_test_FuqZwDxhAMTzH8s91qwxh6A2');

        try {
            $customer = Customer::create(array(
                'email' => $request->stripeEmail,
                'source'  => $request->stripeToken
            ));

            Charge::create (array(
                    "amount" => 3 * 100,
                    "currency" => "usd",
                    'customer' => $customer->id, // obtained with Stripe.js
                    "description" => "Subscription fee." 
            ));
            $user_id = Auth::id();
            $current_date = Carbon::now();
            $subscription = Subscription::where('user_id', $user_id)->firstOrFail();
            $subscription->isSubscribed = "true";
            $subscription->expiry_date =  $current_date->addDays(30);
            $resp = $subscription->save();
            Session::flash ( 'success-message', 'Payment done successfully !' );
            $id = $request->media_id;
            if($id != null){
                return redirect('/listener/media/'.$id);
            }
            return redirect('/listener/index');
        } catch ( \Exception $e ) {
            Session::flash ( 'fail-message', $e->getMessage() );
            return Redirect::back();
        }

        
        if($resp){
            return $this->index();
        }
    }

    public function delete_media($id){
        $user_id = Auth::id();
        $playlist = Playlist::where('user_id', $user_id)->firstOrFail();
        if($playlist == null){
            return $this->user_playlist();
        }
        $medias = json_decode($playlist->medias);
        
        if($medias != null){
            if(($key = array_search($id, $medias)) !== false){
                unset($medias[$key]);
                $playlist->medias = json_encode($medias);
                $playlist->save();
            }
        }    
        return $this->user_playlist();
    }

    public function checkSubscription(){
        $user = Auth::user();
        $today = Carbon::now();
        //dd($user->subscription->expiry_date->gt($today));
        if((strcmp($user->subscription->isSubscribed,"true") == 0) && ($user->subscription->expiry_date->gt($today))){
            return true;
        }

        return false;   
    }

    public function addLikes($id){
        $media = Media::find($id);
        $user_id = Auth::id();
        $likes = json_decode($media->likes);
        if(!in_array($user_id,$likes)){
            array_push($likes,$user_id);
            $media->likes = json_encode($likes);
        } else if(($key = array_search($user_id, $likes)) !== false){
            unset($likes[$key]);
            $media->likes = json_encode($likes);
        }
        $media->save();
        $count = count($likes);
        
        echo json_encode($count);
    }

}
