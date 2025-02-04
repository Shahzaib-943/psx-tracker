<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialiteController extends Controller
{
    public function redirectToSocialiteDriver($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function handleSocialiteDriverCallback($driver)
    {
        try {

            $user = Socialite::driver($driver)->stateless()->user();
            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {

                Auth::login($finduser);
                return redirect()->intended('home');

            } else {
                $newUser = User::updateOrCreate(['email' => $user->email], [
                    'name' => $user->name,
                    'google_id' => $user->id,
                    'password' => encrypt('admin123')
                ]);

                Auth::login($newUser);

                // return redirect()->intended('home');
                return to_route('home');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
