<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ChatModel extends Model
{
	use Notifiable;

    public $table = 'chat';

    protected $fillable = ['sender_id', 'text', 'chat_id'];

    public $timestamps = false;
}
