<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function messages()
    {
        return $this->belongsToMany(User::class, 'messages', 'user_id', 'snedto_user_id');
    }
}
