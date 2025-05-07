<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\MessageEncryption;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    // Automatically encrypt before saving to DB
    public function setMessageAttribute($value)
    {
        if (!preg_match('/^[A-Za-z0-9+\/=]{32,}$/', $value)) {
            $this->attributes['message'] = MessageEncryption::encrypt($value);
        } else {
            $this->attributes['message'] = $value;
        }
    }

    // Automatically decrypt when retrieving from DB
    public function getMessageAttribute($value)
    {
        return MessageEncryption::decrypt($value);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
