<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackSetting extends Model
{
    protected $table = 'feedback_settings';
    protected $fillable = ['interval_days'];
} 