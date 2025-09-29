<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class YandexController extends Controller
{
    public function redirectToYandex() {
        return Socialite::driver('yandex')->redirect();
    }

    public function handleYandexCallback() {
        $yandexUser = Socialite::driver('yandex')->user();

        $user = User::firstOrCreate(
            ['email' => $yandexUser->email],
            [
                'name' => $yandexUser->user['display_name'] ?? $yandexUser->getName(),
                'password' => bcrypt(Str::random(24)),
            ]
        );

        Auth::login($user, true);
        return redirect('/chat');
    }
}
