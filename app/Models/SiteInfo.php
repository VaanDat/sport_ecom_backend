<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'about',
        'refund',
        'parchase_guide',
        'privacy',
        'address',
        'android_app_link',
        'ios_app_link',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'copyright_text'
    ];
}
