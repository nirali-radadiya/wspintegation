<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendWhatsappUser extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];
}
