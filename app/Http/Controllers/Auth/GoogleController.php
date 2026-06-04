<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RandomStringGenerator;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{

    public function __construct(private RandomStringGenerator $generator){

    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $names = splitGoogleName($googleUser->name);

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'hex' => $this->generator->uniqueHexId(),
                'first_name' => $names['first_name'],
                'last_name' => $names['last_name'],
                'google_id' => $googleUser->id,
                'password' => bcrypt(str()->random(24)),
                'avatar' => $googleUser->avatar,   
            ]
        );



        // Automatically verify email for trusted OAuth provider

        $user->email_verified_at = now();
        $user->save();



        // Sign the user into the application

        Auth::login($user);

        return redirect('/profile');


    }


}