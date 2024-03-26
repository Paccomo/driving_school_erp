<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Constants\Role;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// TODO client or employee models
class RegisterController extends Controller
{
    public function __construct() {
        $this->middleware('role:administrator,director');
    }

    public function register(Request $request) {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => [Rule::enum(Role::class)->when(Auth::user()->role == "administrator", fn ($rule) => $rule->only([Role::Client]))],
            'name' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'surname' => ['required', 'regex:/^[\p{L} -]+$/u'],
            'pid' => ['required', 'integer', 'digits:11'],
            'address' => ['required', 'regex:/^[\p{L}. \-\d]+$/u'],
            'city' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'phoneNum' => ['required', 'regex:/^(8|\+370)\s?6\s?\d(?:\s?\d){6}$/'],
        ]);

        $password = Str::password(8, true, true, false, false);

        $account = Account::create([
            'email' => $request->email,
            'password' => Hash::make($request->$password),
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

        return view('auth.display', [
            'name' => "Tomas",
            'surname' => "Pusddkunigis",
            'email' => "tompusk@gmail.com",
            'pw' => "iofgie",
        ]);
    }

    public function showRegistrationForm() {
        $roles = array_combine(array_column(Role::cases(), 'value'), array_column(Role::cases(), 'name'));
        return view('auth.register', ['roles' => $roles, 'roleDirector' => Role::Director->value]);
    }

    public function UserPdf(Request $request) {
        $pdf = Pdf::loadView('auth/displayPdf', [
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'pw' => $request->pw,
        ]);
        return $pdf->download("credentials.pdf");
    }
}
