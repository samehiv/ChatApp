<?php

namespace App\Http\Controllers;

use App\Events\FriendAccepted;
use App\Notifications\FriendRequest;
use App\Notifications\NavFriendRemove;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\User;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('profile_owner')->only('show');
    }
    public function show(User $user)
    {
        return view('user',compact('user'));
    }
    public function search_user()
    {
        $query=request()->input('query');
        $users=User::search($query)->paginate(15);
        return view('search',compact('users'));
    }
    public function get_image(User $user)
    {
        if($user->avatar==null){
            return Storage::disk('local')->url('default.jpg');
        }else{
            return Storage::disk('local')->url($user->avatar);
        }
    }
    public function get_friend_state(User $user)
    {
        if(Auth::user()->isFriend($user)){
            return 'friend';
        }elseif(Auth::user()->hasRequestFrom($user)){
            return 'has_request_from';
        }elseif(Auth::user()->hasRequestTo($user)){
            return 'has_request_to';
        }else{
            return 'not_friend';
        }
    }
    public function delete_friend(User $user)
    {
        Auth::user()->deleteFriend($user);
        foreach (Auth::user()->notifications as $notification){
            if(class_basename($notification->type)=='FriendRequest'
                && $notification->data['userId']==$user->id){
                $notification->delete();
            }
        }
        return;
    }
    public function accept_request(User $user)
    {
        Auth::user()->acceptRequest($user);
        event(new FriendAccepted($user));
        foreach (Auth::user()->notifications as $notification){
            if(class_basename($notification->type)=='FriendRequest'
                && $notification->data['userId']==$user->id){
                $notification->delete();
            }
        }
        return;
    }
    public function send_request(User $user)
    {
        Auth::user()->sendRequest($user);
        $user->notify(new FriendRequest(Auth::user()));
        return;
    }
    public function cancel_request(User $user)
    {
        Auth::user()->cancelRequest($user);
        foreach ($user->notifications as $notification){
            if(class_basename($notification->type)=='FriendRequest'
                && $notification->data['userId']==Auth::user()->id){
                $notification->delete();
            }
        }
        $user->notify(new NavFriendRemove());
        return;
    }
}
