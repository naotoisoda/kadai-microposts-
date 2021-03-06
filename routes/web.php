<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MicropostsController@index');

Route::get('signup','Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup','Auth\RegisterController@register')->name('signup.post');
Route::get('login','Auth\LoginController@showLoginForm')->name('login');
Route::post('login','Auth\LoginController@login')->name('login.post');
Route::get('logout','Auth\LoginController@logout')->name('logout.get');

//ログインしているときのみ実行
Route::group(['middleware'=>'auth'],function(){
    
    //follow処理
    Route::group(['prefix'=>'user/{id}'],function(){
        Route::post('follow','UserFollowController@store')->name('user.follow');
        Route::delete('unfollow','UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings','UserController@followings')->name('users.followings');
        Route::get('followers','UserController@followers')->name('users.followers');
    });
    
    //お気に入り処理
    Route::group(['prefix'=>'user/{id}'],function(){
        Route::get('favorite_posts','UserFavoriteController@favorite_posts')->name('users.favorite_posts');
    });
    
    Route::group(['prefix'=>'micropost/{id}'],function(){ 
    Route::post('favorite','MicropostFavoriteController@store')->name('micropost.favorite');
    Route::delete('unfavorite','MicropostFavoriteController@destroy')->name('micropost.unfavorite');
    Route::get('favorited_users','MicropostFavoriteController@favorited_users')->name('microposts.favorited_users');
    });
    
    //基本処理
    Route::resource('microposts','MicropostsController',['only'=>['store','destroy']]);
    Route::resource('users','UserController',['only'=>['index','show']]);
});
