<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wish extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_id',
        'sender_name',
        'message_text',
        'image',
        'signature',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'qr_id', 'qr_id');
    }
}