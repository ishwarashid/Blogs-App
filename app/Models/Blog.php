<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\BlogFactory> */
    use HasFactory;
    protected $fillable = ['title', 'content', 'blog_image_url', 'user_id'];

    // mutator
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value); 
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = ucfirst($value); 
    }

    // accesstor
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->setTimezone('Asia/Karachi')->format('l, j M Y h:i A');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
