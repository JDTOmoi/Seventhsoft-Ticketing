<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientChat extends Model
{
    protected $fillable = [
        'ticketID',
        'type',
        'response'
    ];

    public function attachments() {
        return $this->hasMany(ClientAttachment::class, 'clientChatID');
    }
}