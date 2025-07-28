<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTicket extends Model
{
    protected $fillable = [
        'userID',
        'appID',
        'title'
    ];
}
