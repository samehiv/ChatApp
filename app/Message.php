<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable=['body','seen','date','sender','receiver'];
    public function sender()
    {
        return $this->belongsTo(User::class,'sender');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver');
    }
}
