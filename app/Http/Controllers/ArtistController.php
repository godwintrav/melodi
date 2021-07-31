<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Channel;
use App\Model\Media;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Intervention\Image\ImageManager;
// use FFMpeg;

class ArtistController extends Controller
{
    public function index($medias = null){
        
        $user = Auth::user();
        $channel_id = $user->channel->id;
        $medias = Media::orderBy('created_at', 'desc')
                        ->where('channel_id',$channel_id)->get();
        //dd($medias);
        if($medias != null){
            return view('artist.index', ['medias' => $medias]);
        }
        return view('artist.index');
        
    }

    public function logout(){

        Auth::logout();
        return redirect('/viewer_login');
    }

    public function createChannelView(){
        $user = Auth::user();
        if($user->channel->active == 1){
            $msg = "created";
            return view('artist.create-channel', ['msg' => $msg]);
        } else{
            return view('artist.create-channel');
        }
    }

    public function create_channel(Request $request){
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'img' => 'required',
        ]);

        $extension = $request->file('img')->getClientOriginalExtension();
        //dd($extension);

        if(!$this->validateImgExtension($extension)){

            $err_msg = "Unsupported Image Format";
            return $this->redirectPage('artist.create-channel',$err_msg);

        }

        $path = $request->file('img')->store('images');

        $manager = new ImageManager();
        //dd(asset('storage/'.$path));
        
        $image = $manager->make('storage/'.$path)->resize(80, 80);
        $image->save('storage/'.$path);
        
        $user_id = Auth::id();
        $channel = Channel::where('user_id', $user_id)->first();
        if($channel == null){
           return $this->redirectPage("artist.create-channel", "Error Creating Channel");
        }
        $channel->title = $data['title'];
        $channel->description = $data['description'];
        $channel->active = 1;
        $channel->img = $path;
        $resp = $channel->save();
        if($resp){
            return redirect("/artist/index");
        } else{
            return $this->redirectPage("artist.create-channel", "Error Creating Channel");
        }
    }

    public function redirectPage($view, $err_msg = null){
        if($err_msg == null){
            return view($view);
        }
        return view($view, ['err_msg' => $err_msg]);
    }

    public function setUploadMusic(Request $request){

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'tags' => 'required|string|max:255',
            'thumbnail' => 'required',
        ]);     


        $extension = $request->file('thumbnail')->getClientOriginalExtension();
        //dd($extension);

        if(!$this->validateImgExtension($extension)){

            $err_msg = "Unsupported Image Format";
            return $this->redirectPage('artist.upload-music',$err_msg);

        }

        $path = $request->file('thumbnail')->store('images');

        $manager = new ImageManager();
        //dd(asset('storage/'.$path));
        
        $image = $manager->make('storage/'.$path)->resize(255, 240);
        $image->save('storage/'.$path);

        

        $user_id = Auth::id();
        
        $channel = Channel::where('user_id', $user_id)->first();
        if($channel == null){
            return $this->redirectPage('artist.upload-music', "Error uploading details");
        }
        $channel_id = $channel->id;

        $media = Media::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'tags' => $data['tags'],
            'channel_id' => $channel_id,
            'type' => 'audio',
            'views' => json_encode([]),
            'thumbnail' => $path,
            'royalties' => 0,
            'likes' => json_encode([]),
        ]);

        $media_id = $media->id;
        return redirect('/artist/upload/'.$media_id);
    }

    public function uploadMusic(Request $request){
        //dd($request->filename);
        $data = $request->validate([
            'filename' => 'required',
            'media_id' => 'required',
        ]);
        
        $media_id = $data['media_id'];
        $extension = $request->file('filename')->getClientOriginalExtension();
        //dd($extension);

        if(!$this->validateMusicExtension($extension)){

            $err_msg = "Unsupported File Format";
            return $this->redirectToUploadView($media_id, $err_msg);

        }
        
        $path = $request->file('filename')->store('audios');
        
        // $ffprobe = FFMpeg\FFProbe::create();
        // $time_seconds = $ffprobe
        //             ->format('storage/'.$path) // extracts file informations
        //             ->get('duration');
        // $duration = gmdate("H:i:s", $time_seconds);

        $media = Media::find($media_id);
        if($media == null){
            return $this->redirectToUploadView($media_id, "Error Uploading Song");
        }
        $media->filename = $path;
        // $media->duration = $duration;
        $media->save();
        return redirect('/artist/index');
    }

    public function redirectToUploadView($id, $err_msg = null){
        return view('artist.upload', ['media_id' => $id, 'err_msg' => $err_msg]);
    }

    public function validateMusicExtension($ext){
        $extensions = array("mp3","ogg", "wav");
        foreach($extensions as $extension){
            if(strcmp($extension,strtolower($ext)) == 0){
                return true;
            }
        }                  
        return false;
    }

    public function validateImgExtension($ext){
        $extensions = array("jpg","jpeg", "gif", "png", "apng", "svg", "bmp", "ico", "png", "ico");
        foreach($extensions as $extension){
            if(strcmp($extension,strtolower($ext)) == 0){
                return true;
            }
        }                  
        return false;
    }

    public function viewMedia($id){
        $media = Media::findOrFail($id);
        $vid_tags = explode(",", $media->tags);
        $views = json_decode($media->views);
        //dd($views);
        $num_views = count($views);
        $user = Auth::user();
        $channel_id = $user->channel->id;
        if($num_views == 0 || $num_views == 1){
            $num_views = $num_views ." view";
        } else{
            $num_views = $num_views ." views";
        }

        $upNexts = Media::orderBy('created_at', 'desc')
                        ->where('id','<>',$media->id)
                        ->where('channel_id',$channel_id)
                        ->inRandomOrder()
                        ->limit(6)
                        ->get();

        return view("artist.video-page", ['media' => $media, 'vid_tags' => $vid_tags, 'num_views' => $num_views, 'upNexts' => $upNexts]);
    }

    public function myChannel(){
        
        $user = Auth::user();
        $channel_id = $user->channel->id;
        $medias = Media::orderBy('created_at', 'desc')
                    ->where('channel_id',$channel_id)->get();
        //dd($medias);
        if($medias != null){
            return view('artist.single-channel', ['medias' => $medias]);
        }
        return view('artist.single-channel');
        
    }

    public function delete_media($id){
        $media = Media::find($id);

        if($media == null){
            return $this->myChannel();
        }
        $media->delete();
        return $this->myChannel();
    }

    public function searchVideo(Request $request){
        $data = $request->validate([
            'search' => 'required|string',
        ]);
        $user = Auth::user();
        $channel_id = $user->channel->id;
        //dd($channel_id);
        $searchVal = $data['search'];
        $medias = Media::orderBy('created_at', 'desc')
                        ->where('channel_id',$channel_id)
                        ->where('title','LIKE','%'.$searchVal.'%')
                        ->orWhere("tags",'LIKE', '%'.$searchVal.'%')
                        ->orWhere("description",'LIKE', '%'.$searchVal.'%')
                        ->get();
        //dd($medias);
        if(count($medias) > 0){
            return $this->renderSearch($searchVal, $medias);
        }else{
            return $this->renderSearch($searchVal);
        }
    }

    public function renderSearch($input, $medias = null){

        if(isset($medias)){
            return view('artist.index', ['medias' => $medias, 'input' => $input]);
        }else{
            return view('artist.index',['input' => $input]);
        }
    }

    
}
