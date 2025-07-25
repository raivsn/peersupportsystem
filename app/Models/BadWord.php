<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadWord extends Model
{
    protected $table = 'bad_words';
    protected $fillable = ['word'];
}
