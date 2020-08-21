<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserChatTable extends Model
{
	use Notifiable;

    public $table = 'user_chat_table';

    protected $fillable = ['chat_id', 'user_id'];

    public $timestamps = false;

    public function getChats()
    {
    	return $this->belongsTo('App\ChatTable', 'chat_id', 'chat_id');
    }
}
