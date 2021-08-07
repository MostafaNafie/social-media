<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'color',
        'body',
        'created_by'
    ];

    public static $colors = [
        'blue',
        'red',
        'green'
    ];

    protected $appends = [
        'comments_count'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return  $this->hasMany(Comment::class, 'post_id');
    }

    public function getCommentsCountAttribute()
    {
        if (Redis::get("post:{$this->id}:comments:count")) {
            return (int)Redis::get("post:{$this->id}:comments:count");
        }

        $count = $this->comments()->count();
        Redis::set("post:{$this->id}:comments:count", $count);
        return (int)$count;
    }

}
