<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request): RedirectResponse {
        $remember = $request->has('remember');

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $account = Account::with('client')->where('email', $credentials['email'])->first();
        if ($account != null && $account->client != null && $account->client->currently_studying != 1) {
            return back()->withErrors([
                'email' => 'Ši paskyra pažymėta kaip pabaigusi kursus. Jei manote, kad tai klaida - susisiekite su savo kursų filialu.',
            ]);
        }
 
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
 
            return redirect()->intended('/');
        }
 
        return back()->withErrors([
            'email' => 'Neteisingi duomenys.',
        ])->onlyInput('email');
    }

    public function showLoginForm() {
        return view('auth.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
