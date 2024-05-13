<?php

namespace App\Http\Controllers\Auth;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Person;
use App\Rules\validateOldPw;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PwController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showForm(Request $request) {
        $account = $request->id;
        if ($account !== null){
            if (in_array(Auth::user()->role, [Role::Director->value, Role::Administrator->value])) {
                $account = Account::find($account);
                if ($account->role != Role::Client->value && Auth::user()->role == Role::Administrator->value) 
                    abort(Response::HTTP_FORBIDDEN, 'Keisti slaptažodžius galima tik klientų.');
                $person = Person::find($account->id);
                if ($person !== null) {
                    $account-> name = $person->name . " " . $person->surname;
                }
            } else {
                abort(Response::HTTP_FORBIDDEN, 'Access denied.');
            }
        }
        return view('auth.changePw', ['account' => $account]);
    }

    public function save(Request $request) {
        if ($request->id != null) {
            if (!in_array(Auth::user()->role, [Role::Director->value, Role::Administrator->value])) {
                abort(Response::HTTP_FORBIDDEN, 'Access denied.');
            }
        }

        $request->validate([
            'id' => ['integer', 'gt:0', 'exists:account,id'],
            'old' => ['string'],
            'new' => ['required','min:6','confirmed']
        ]);
        $userID = $request->has('id') ? $request->id : Auth::user()->id;
        $request->validate(['old' => new validateOldPw($userID, $request->old)]);

        $acc = Account::find($userID);
        if (Auth::user()->role == Role::Administrator->value) {
            if (($acc->role != Role::Client->value) && Auth::user()->id != $userID) {
                abort(Response::HTTP_FORBIDDEN, 'Keisti slaptažodžius galima tik klientų.');
            }
        }
        $acc->password = Hash::make($request->new);
        $acc->save();

        if ($acc->id == Auth::user()->id)
            $routeToReturn = 'home';
        else if($acc->role == Role::Client->value)
            $routeToReturn = 'client.list';
        else
            $routeToReturn = 'employee.list';

        return redirect()->route($routeToReturn)->with('success', 'Slaptažodis sėkmingai pakeistas');
    }
}
