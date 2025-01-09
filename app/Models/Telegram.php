<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telegram extends Model
{

    protected $table = 'telegram';
    protected $fillable = [
        'name',
        'chat_id',
        'email'
    ];

}
