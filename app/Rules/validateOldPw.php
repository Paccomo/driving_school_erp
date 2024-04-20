<?php

namespace App\Rules;

use App\Models\Account;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class validateOldPw implements ValidationRule
{
    private $accID;
    private $pw;

    public function __construct($acc, $pw)
    {
        $this->accID = $acc;
        $this->pw = $pw;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $account = Account::find($this->accID);
        if (!Hash::check($this->pw, $account->password)) {
            $fail('Įvestas slaptažodis neteisingas');
        }
    }
}
