<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index(){
        $users=User::orderBy('id','desc')->paginate(10);
        return view('users.index',['users'=>$users]);
    }
    public function show($id){
        $user=User::findOrfail($id);
        $user->loadRelationshipCounts();
        $microposts=$user->microposts()->orderBy('created_at','desc')->paginate(10);
        
        return view('users.show',['user'=>$user,'microposts'=>$microposts,]);
    }
}
