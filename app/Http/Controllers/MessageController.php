<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Message;
use App\Events\MessageSend;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function send_msg()
    {
        $user=request()->input('user');
        $body=request()->input('msg');
        $msg=new Message;
        $msg->sender=Auth::user()->id;
        $msg->receiver=$user;
        $msg->body=$body;
        $msg->date=Carbon::now();
        $msg->save();
        event(new MessageSend($msg,'App.User.'.$user));
        return $msg;
    }
    public function read_msg(Message $message)
    {
        $message->update(['seen'=>true]);
        return;
    }
    public function chat_msgs(User $user)
    {
        $msg=Auth::user()->chatMsgs($user);
        return $msg;
    }
    public function read_chat(User $user)
    {
        Auth::user()->receivedMsgs()->where('sender',$user->id)->update(['seen'=>true]);
        return;
    }
    public function unread_msgs_count()
    {
        return Auth::user()->receivedMsgs()->where('seen',false)->count();
    }
    public function get_msgs()
    {
       return Auth::user()->getMsgs();
    }
    public function read_msgs()
    {
        Auth::user()->receivedMsgs()->update(['seen'=>true]);
        return;
    }
}
