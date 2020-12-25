<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserFavoriteController extends Controller
{
    public function favorite_posts($id)
    {
        $user = User::findOrfail($id);
        
        $user->loadRelationshipCounts();
        
        $favorite_posts = $user->favorite_posts()->paginate(10);
        
        return view('users.favorite_posts',[
            'user' => $user,
            'microposts' => $favorite_posts,
            ]);
    }
}
