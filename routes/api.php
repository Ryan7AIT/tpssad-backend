<?php

use App\Mail\newpassword;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

function Cipher($ch, $key)
{
	if (!ctype_alpha($ch))
		return $ch;

	$offset = ord(ctype_upper($ch) ? 'A' : 'a');
	return chr(fmod(((ord($ch) + $key) - $offset), 26) + $offset);
}

function Encipher($input, $key)
{
	$output = "";

	$inputArr = str_split($input);
	foreach ($inputArr as $ch)
		$output .= Cipher($ch, $key);

	return $output;
}




Route::get('/', function() {
    return Encipher("abcs",2);
});

Route::post('/signup', function() {

    $user = User::create([
        'name' => Encipher(request('name'),3),
        'password' => Encipher(request('password'),3),
        'email' => Encipher(request('email'),3)


    ]);

    return $user;

});


Route::post('/login', function() {

    $attr = request()->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    $user = User::where('email', Encipher($attr['email'],3))->where('password', Encipher($attr['password'],3))->first();

    if(! $user  ) {
        return response('wrong data');
    }

    // return 'goood';
    // return response('logedin succesffly', 200);

    // $r =

    return response($user, 200);


});


Route::post('/messages', function() {

    $a = request('usera');
    $b = request('userb');

    Message::create([
        'user_id' => $a,
        'snedto_user_id' => $b,
        'message' => request('message'),
        'methode' => request('methode'),
        'valueofa' => request('valueofa'),
        'valueofb' => request('valueofb'),
        'valueofn' => request('valueofn'),
        'valueofk' => request('valueofk'),




    ]);

    return 'goood mesg send';



} );


// get all msgs for a given user

Route::get('/messages/{id}', function($id) {



    // $user = User::find($id);

    // return User::find(1);


    // todo

    $msgs = Message::where('snedto_user_id', $id);



    return $msgs->get();


});

Route::get('/message/{id}', function($id) {

    // $user = User::find($id);

    // return User::find(1);


    // todo

    $msgs = Message::where('id', $id)->first();



    return $msgs;


});


Route::get('/user/{id}', function($id) {

    $u = User::find($id);

    return $u->name;
});

// get all users

Route::get('users', function() {
    return User::all();
});


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


Route::post('newpassword', function() {

    // return $email;

    $email =  request('email');

    $characters = 'abcdefghijklmnopqrstuvwxyz';

    $randomString = '';



//
    // $newpassword =  $this.generateRandomString();
    $charactersLength = strlen($characters);;

    for ($i = 0; $i < 10; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    // return $randomString;


    Mail::to($email)->send(new newpassword($randomString));



    $user = User::where('email', Encipher($email,3))->first();


    $user->password = Encipher($randomString,3);

    $user->save();

    return $user;

    return 'DONE';
});


// get the name of a given user

Route::get('/user/{id}', function($id) {

    $user = User::find($id);

    // return User::find(1);


    // todo

    // $msgs = Message::where('snedto_user_id', $id);



    return  response($user,200);

    // return $msgs->get();


});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
