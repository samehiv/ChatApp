<?php

namespace App\Http\Controllers;

use App\Message;
use App\Notifications\FriendRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Events\MsgSent;
use App\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Auth::check()){
            return view('home',compact('friendNav','msgNav'));
        }else{
            return view('home');
        }

    }
    public function store_image()
    {
        $img=request()->file('avatar')->store('public');
        $resize_img=storage_path('app/'.$img);
        list ($w,$h)=getimagesize($resize_img);
        $thumb=imagecreatetruecolor(150,150);
        $resource=imagecreatefromjpeg($resize_img);
        imagecopyresized($thumb,$resource,0,0,0,0,150,150,$w,$h);
        imagejpeg($thumb,$resize_img,100);
        Auth::user()->avatar=$img;
        Auth::user()->save();
        return;
    }
    public function delete_image()
    {
        Auth::user()->avatar=null;
        Auth::user()->save();
        return;
    }
    public function unread_reqs()
    {
        return Auth::user()->unreadNotifications()->
        where('type','App\Notifications\FriendRequest')->count();
    }
    public function reqs()
    {
        return Auth::user()->notifications()->where('type','App\Notifications\FriendRequest')->get();
    }
    public function read_reqs()
    {
        Auth::user()->unreadNotifications()->
        where('type','App\Notifications\FriendRequest')->get()->markAsRead();
        return;
    }
    public function get_user(User $user)
    {
        return $user;
    }
    public function get_friends()
    {
        return Auth::user()->friends();
    }




}
