<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAttachment extends Model
{
    protected $fillable = [
        'name',
        'extension',
        'clientChatID'
    ];
}
