<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Constants\TimetableTimeType;

class timeAfter implements ValidationRule
{
    protected $request;
    protected $weekday;
    protected $type;

    public function __construct($request, $weekday, $type)
    {
        $this->request = $request;
        $this->type = $type;
        $this->weekday = $weekday;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $openTime = $this->weekday . "_" . TimetableTimeType::Open->value;
        if ($this->request->$openTime == null || strtotime($value) <= strtotime($openTime)) {
            $fail($this->type . ' turi būti po atidarymo');
        }
    }
}
