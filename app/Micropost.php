<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable =['content'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    //お気に入りしているユーザーを取得（投稿→ユーザー）
    public function favorited_users()
    {
        return $this->belongsToMany(User::class,'favorites','micropost_id','user_id')->withTimestamps();
    }
    public function loadRelationshipCounts()
    {
        return loadCount('favorited_users');
    }
}
