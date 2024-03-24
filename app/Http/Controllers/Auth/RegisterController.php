<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Constants\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Account;

// TODO Translations
// TODO form rearrange
// TODO Only admin and director register
// TODO enums by role
class RegisterController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => [Rule::enum(Role::class)],
            'name' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'surname' => ['required', 'regex:/^[\p{L} -]+$/u'],
            'pid' => ['required', 'integer', 'digits:11'],
            'address' => ['required', 'regex:/^[\p{L}. \-\d]+$/u'],
            'city' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'phoneNum' => ['required', 'regex:/^(8|\+370)\s?6\s?\d(?:\s?\d){6}$/'],
        ]);

        $account = Account::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $person = new Person();
        $person->id = $account->id;
        $person->name = encrypt($request->name);
        $person->surname = encrypt($request->surname);
        $person->pid = encrypt($request->pid);
        $person->address = encrypt($request->address . ", " . $request->city);
        $person->phone_number = encrypt($request->phoneNum);
        $person->save();

        // Create either client or employee

        return redirect()->route('login');
    }

    public function showRegistrationForm() {
        $roles = array_combine(array_column(Role::cases(), 'value'), array_column(Role::cases(), 'name'));
        return view('auth.register', ['roles' => $roles]);
    }
}
