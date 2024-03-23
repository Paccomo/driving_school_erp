<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Account;

class RegisterController extends Controller
{
   public function register(Request $request) {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:account'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $acc = Account::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login');
   }

   public function showRegistrationForm() {
        return view('auth.register');
   }
}
