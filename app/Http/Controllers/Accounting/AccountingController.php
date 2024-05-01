<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function __construct() {
        $this->middleware('role:accountant,director');
    }

    public function salariesForm() {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $salaries = Expense::where('reason', 'LIKE', "Darbo užmokestis už $currentYear metų $currentMonth mėnesį.%")->get();
        $salariesRegistered = $salaries->isNotEmpty();

        $employees = Employee::with(['person', 'account'])
            ->whereHas('account', function ($query) {
                $query->where('role', '<>', 'director');
            })
            ->get();
        return view('accounting.salariesForm', ['salariesRegistered' => $salariesRegistered, 'employees' => $employees]);
    }

    public function salaries(Request $request) {
        $rules = [];
        $inputs = [];
        $employeeIDs = [];
        foreach ($request->except('_token') as $inputName => $value) {
            if (strpos($inputName, 'salary') === 0) {
                $rules[$inputName] = ['numeric', 'gt:0'];
                $employeeID = substr($inputName, strlen('salary'));
                $inputs[] = ['employee' => $employeeID, 'salary' => $value];
                $employeeIDs[] = (int)$employeeID;
            }
        }
        $request->validate($rules);

        $currentDate = Carbon::now('Europe/Vilnius');
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $reasonBegining = "Darbo užmokestis už $currentYear metų $currentMonth mėnesį.\n";

        $employees = Person::whereIn('id', $employeeIDs)->get()->keyBy('id')->toArray();
        foreach ($inputs as $i) {
            $expense = new Expense();
            $expense->paid_at = $currentDate;
            $expense->amount = (float)$i['salary'];

            $name = $employees[$i['employee']]['name'];
            $surname = $employees[$i['employee']]['surname'];
            $expense->reason = $reasonBegining . "Darbuotoja (-s) " . $name . " " . $surname . ".";

            $expense->save();
        }
        return redirect()->route('home')->with('success', "Šio mėnesio darbo vietų išlaidos įvestos sėkmingai");
    }

    public function expense(Request $request) {
        $this->validateReceipt($request);
        $expense = new Expense();
        $expense->amount = $request->amount;
        $expense->reason = $request->reason;
        $expense->paid_at = $request->receiptDate;
        $expense->save();
        return redirect()->route('accounting.expense')->with('success', "Išlaida įvesta");
    }

    public function expenseForm() {
        return view('accounting.receiptForm', ["formRoute" => 'accounting.expense.save', 'title' => "Įvesti naują išlaidą"]);
    }

    public function income(Request $request) {
        $this->validateReceipt($request);
        $income = new Income();
        $income->amount = $request->amount;
        $income->reason = $request->reason;
        $income->received_at = $request->receiptDate;
        $income->save();
        return redirect()->route('accounting.income')->with('success', "Pajamos įvestos");
    }

    public function incomeForm() {
        return view('accounting.receiptForm', ["formRoute" => 'accounting.income.save', 'title' => "Įvesti naujas pajamas"]);
    }

    protected function validateReceipt(Request $request) {
        $request->validate([
            'receiptDate' => ['required', 'date', 'date_format:Y-m-d'],
            'reason' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'gt:0']
        ]);
    }
}
