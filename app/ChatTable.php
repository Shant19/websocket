<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ChatTable extends Model
{
	use Notifiable;

    public $table = 'chat_table';

    protected $fillable = ['creator_id', 'name', 'chat_id'];

    public $timestamps = false;

    public function getChats()
    {
    	return $this->hasMany('App\ChatTable', 'chat_id', 'chat_id');
    }
}
