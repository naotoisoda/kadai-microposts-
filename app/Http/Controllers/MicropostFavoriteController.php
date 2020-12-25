<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Micropost;

class MicropostFavoriteController extends Controller
{
    public function store($id)
    {
        \Auth::user()->favorite($id);
        return back();
    }
    
    public function destroy($id)
    {
        \Auth::user()->unfavorite($id);
        return back();
    }
    
    public function favorited_users($id)
    {
        $micropost = Micropost::findOrfail($id);
        
        $micropost->loadRelationshipCounts();
        
        $favorited_users = $micropost->favorited_users()->paginate(10);
        
        return view('microposts.favorited_users',[
            'micropost' => $micropost,
            'users' => $favorited_users,
            ]);
        
    }
}
