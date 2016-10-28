<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test',function(){
    return view('test');
});




Auth::routes();


Route::get('/home', 'HomeController@index');
Route::post('/home/storeimg','HomeController@store_image');
Route::post('/home/deleteimg','HomeController@delete_image');
Route::get('/home/unreadreqs','HomeController@unread_reqs');
Route::get('/home/reqs','HomeController@reqs');
Route::get('/home/readreqs','HomeController@read_reqs');
Route::get('/home/getuser/{user}','HomeController@get_user');
Route::get('/home/getfriends','HomeController@get_friends');
Route::post('/message/sendmsg','MessageController@send_msg');
Route::get('/message/readmessage/{message}','MessageController@read_msg');
Route::get('/message/getchatmsgs/{user}','MessageController@chat_msgs');
Route::get('/message/readchat/{user}','MessageController@read_chat');
Route::get('/message/getmsgs','MessageController@get_msgs');
Route::get('/message/unreadmsgs','MessageController@unread_msgs_count');
Route::get('/message/readmsgs','MessageController@read_msgs');
Route::get('/user/{user}/getimg','UserController@get_image');
Route::get('/user/{user}','UserController@show');
Route::get('/search','UserController@search_user');
Route::get('/user/{user}/friendstate','UserController@get_friend_state');
Route::get('/user/{user}/deletefriend','UserController@delete_friend');
Route::get('/user/{user}/acceptrequest','UserController@accept_request');
Route::get('/user/{user}/cancelrequest','UserController@cancel_request');
Route::get('/user/{user}/sendrequest','UserController@send_request');




