<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['first_name', 'last_name', 'phone_number'];

    public function emails()
    {
        return $this->hasMany(Email::class);
    }
}
