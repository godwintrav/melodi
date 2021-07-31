<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth:admin']], function(){

    Route::view("/admin/upload-advert", 'admin.upload-advert');
    Route::view("/admin/test", 'admin.test');
    

    Route::post('/admin/upload_advert', 'AdminController@UploadAdvert');
    Route::post('/admin/upload_media', 'AdminController@uploadVideo');
    Route::post("/admin/search", 'AdminController@searchVideo');
    
    //goes to index page of admin 
    Route::get('/admin/ads', 'AdminController@allAds');
    Route::get('/admin/index', 'AdminController@index');
    Route::get('/admin/logout', 'AdminController@logout');
    Route::get('/admin/upload/{id}', 'AdminController@redirectToUploadView');
    Route::get("/admin/video/{id}", 'AdminController@viewMedia');
    Route::get("/admin/paid/{id}", 'AdminController@confirmPay');
    Route::get("/admin/activateAd/{id}", 'AdminController@activateAd');
    Route::get("/admin/disableAd/{id}", 'AdminController@disableAd');
    Route::get("/admin/royalties", 'AdminController@allRoyalties');
    Route::get("/admin/deleteUser/{id}", 'AdminController@deleteUser');
    Route::get("/admin/deleteAd/{id}", 'AdminController@deleteAd');

    
});

Route::group(['middleware' => ['auth:listener']], function(){
    //goes to index page of viewer
    Route::get('/listener/index', 'ListenerController@index');
    Route::get('/listener/logout', 'ListenerController@logout');
    Route::get("/listener/media/{id}", 'ListenerController@viewMedia');
    Route::get("/listener/ad/{id}", 'ListenerController@loadAdPage');
    Route::get("/listener/viewChannel/{id}", 'ListenerController@viewChannel');
    Route::get("/listener/channels", 'ListenerController@allChannels');
    Route::get("/listener/addPlaylist/{id}", 'ListenerController@addToPlaylist');
    Route::get("/listener/playlist", 'ListenerController@user_playlist');
    Route::get("/listener/delete_media/{id}", 'ListenerController@delete_media');
    Route::get("/listener/addLikes/{id}", 'ListenerController@addLikes');   
    Route::get("/listener/payment/{id}", 'ListenerController@paymentPage'); 

    Route::post("/listener/search", 'ListenerController@searchVideo');
    Route::post('/listener/confirm-payment', 'ListenerController@confirmPayment');
    
    
    

});

Route::group(['middleware' => ['auth:artist']], function(){
    //goes to index page of viewer
    Route::get("/", 'ArtistController@index');
    Route::get('/artist/index', 'ArtistController@index');
    Route::get('/artist/logout', 'ArtistController@logout');
    Route::get("/artist/create-channel", 'ArtistController@createChannelView');
    Route::get("/artist/media/{id}", 'ArtistController@viewMedia');
    Route::get('/artist/upload/{id}', 'ArtistController@redirectToUploadView');
    Route::get("/artist/myChannel", 'ArtistController@myChannel');
    Route::get("/artist/delete_media/{id}", 'ArtistController@delete_media');

    

    Route::post("/artist/search", 'ArtistController@searchVideo');
    Route::post("/artist/create_channel", 'ArtistController@create_channel');
    Route::post('/artist/set_upload_music', 'ArtistController@setUploadMusic');
    Route::post('/artist/upload_media', 'ArtistController@uploadMusic');

    Route::view("/artist/upload-music", 'artist.upload-music');
    

    
    
    
});

//Route::get('/{num}','Tutorial@show')->middleware('age');

Route::view("/admin_login", 'admin.login')->name('admin_login');
Route::view("/admin_register", 'admin.register');
Route::view("/viewer_register", 'register');
Route::view("/viewer_login", 'login')->name('viewer_login');
Route::view("/", 'login');

//Route::view("/admin/upload", 'admin.upload')->middleware('auth');

Auth::routes();

//Route::get('/home', 'UserController@index')->name('home');




Route::post('/register_admin', 'AdminController@register');
Route::post('/login_admin', 'AdminController@login');
Route::post('/register_viewer', 'UserController@register');
Route::post('/login_viewer', 'UserController@login');

