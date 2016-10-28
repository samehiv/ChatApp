<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable,Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function sendingFriends()
    {
        return $this->belongsToMany(User::class,'friends','friend_id','user_id');
    }
    public function receivingFriends()
    {
        return $this->belongsToMany(User::class,'friends','user_id','friend_id');
    }

    public function friends()
    {
        $friends=$this->sendingFriends()->wherePivot('accepted',true)->get()->
            merge($this->receivingFriends()->wherePivot('accepted',true)->get());
        return $friends;
    }

    public function sendRequest(User $user)
    {
        $this->receivingFriends()->attach($user->id);
    }

    public function acceptRequest(User $user)
    {
        $this->sendingFriends()->updateExistingPivot($user->id,['accepted'=>true]);
    }

    public function cancelRequest(User $user)
    {
        $this->receivingFriends()->detach($user->id);
    }

    public function isFriend(User $user)
    {
        return (bool) $this->friends()->where('id',$user->id)->count();
    }

    public function deleteFriend(User $user)
    {
        $this->receivingFriends()->detach($user->id);
        $this->sendingFriends()->detach($user->id);
    }

    public function receivedRequests()
    {
        return $this->sendingFriends()->wherePivot('accepted',false)->get();
    }
    public function sentRequests()
    {
        return $this->receivingFriends()->wherePivot('accepted',false)->get();
    }

    public function hasRequestFrom(User $user)
    {
        return (bool) $this->receivedRequests()->where('id',$user->id)->count();
    }

    public function hasRequestTo(User $user)
    {
        return (bool) $this->sentRequests()->where('id',$user->id)->count();
    }

    public function sentMsgs()
    {
        return $this->hasMany(Message::class,'sender');
    }

    public function receivedMsgs()
    {
        return $this->hasMany(Message::class,'receiver');
    }
    public function chatMsgs(User $user)
    {
        $msgs=$this->sentMsgs()->where('receiver',$user->id)->get()
            ->merge($this->receivedMsgs()->where('sender',$user->id)->get());
         return $msgs->sortBy('created_at')->values()->all();
    }
    public function readMsgs()
    {
        $this->receivedMsgs()->where('seen',false)->update(['seen'=>true]);
    }
    public function getMsgs()
    {
        return $this->receivedMsgs()->get()->sortByDesc('created_at')->groupBy('sender')->values()->all();
    }








}
