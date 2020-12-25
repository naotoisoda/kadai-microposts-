<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    public function followings()
    {
        return $this->belongsToMany(User::class,'user_follow','user_id','follow_id')->withTimestamps();
    }
    public function followers()
    {
        return $this->belongsToMany(User::class,'user_follow','follow_id','user_id')->withTimestamps();
    }
    public function follow($userId)
    {
        $exist=$this->is_following($userId);
        $its_me=$this->id==$userId;
        
        if($exist||$its_me)
        {
            return false;
        }
        else
        {
            $this->followings()->attach($userId);
            return true;
        }
    }
    public function unfollow($userId)
    {
        $exist=$this->is_following($userId);
        $its_me=$this->id==$userId;
        
        if($exist&&!$its_me)
        {
            $this->followings()->detach($userId);
            return true;
        }
        else
        {
            return false;
        }
    }
    public function is_following($userId)
    {
        return $this->followings()->where('follow_id',$userId)->exists();
    }
    
    public function feed_microposts()
    {
        $userIds = $this->followings()->pluck('users.id')->toArray();
        
        $userIds[] = $this->id;
        
        return Micropost::whereIn('user_id',$userIds);
    }
    
    //*******お気に入り管理**************************************************
    //お気に入りしている投稿を取得（ユーザー→投稿）
    public function favorite_posts()
    {
        return $this->belongsToMany(Micropost::class,'favorites','user_id','micropost_id')->withTimestamps();
    }

    //投稿をお気に入り
    public function favorite($micropostId)
    {
        $exist = $this->is_favorited($micropostId);
        
        if($exist){
            //既にお気に入りしていたらなにもしない
            return false;
        }else{
            //お気に入りする
            $this->favorite_posts()->attach($micropostId);
            return true;
        }
    }
    
    //投稿をお気に入り解除
    public function unfavorite($micropostId)
    {
        $exist = $this->is_favorited($micropostId);        
        
        if(!$exist){
            //お気に入りしていなかったらなにもしない
            return false;
        }else{
            //お気に入り解除する
            $this->favorite_posts()->detach($micropostId);
            return true;
        }        
    }
    
    //既にお気に入り済みかを確認
    public function is_favorited($micropostId)
    {
        return $this->favorite_posts()->where('micropost_id',$micropostId)->exists();
    }
    //********************************************************************
    
    //count
    public function loadRelationshipCounts()
    {
        $this->loadCount('microposts','followings','followers','favorite_posts');
    }
    
}
