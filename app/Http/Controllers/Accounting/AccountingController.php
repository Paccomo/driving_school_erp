<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:accountant,director');
    }

    public function salariesForm()
    {
        $currentYear = Carbon::now('Europe/Vilnius')->year;
        $currentMonth = Carbon::now('Europe/Vilnius')->month;
        $salaries = Expense::where('reason', 'LIKE', "Darbo užmokestis už $currentYear metų $currentMonth mėnesį.%")->get();
        $salariesRegistered = $salaries->isNotEmpty();

        $employees = Employee::with(['person', 'account'])
            ->whereHas('account', function ($query) {
                $query->where('role', '<>', 'director');
            })
            ->get();
        return view('accounting.salariesForm', ['salariesRegistered' => $salariesRegistered, 'employees' => $employees]);
    }

    public function salaries(Request $request)
    {
        $rules = [];
        $inputs = [];
        $employeeIDs = [];
        foreach ($request->except('_token') as $inputName => $value) {
            if (strpos($inputName, 'salary') === 0) {
                $rules[$inputName] = ['numeric', 'gt:0'];
                $employeeID = substr($inputName, strlen('salary'));
                $inputs[] = ['employee' => $employeeID, 'salary' => $value];
                $employeeIDs[] = (int) $employeeID;
            }
        }
        $request->validate($rules);

        $currentDate = Carbon::now('Europe/Vilnius');
        $currentYear = Carbon::now('Europe/Vilnius')->year;
        $currentMonth = Carbon::now('Europe/Vilnius')->month;
        $reasonBegining = "Darbo užmokestis už $currentYear metų $currentMonth mėnesį.\n";

        $employees = Person::whereIn('id', $employeeIDs)->get()->keyBy('id')->toArray();
        foreach ($inputs as $i) {
            $expense = new Expense();
            $expense->paid_at = $currentDate;
            $expense->amount = (float) $i['salary'];

            $name = $employees[$i['employee']]['name'];
            $surname = $employees[$i['employee']]['surname'];
            $expense->reason = $reasonBegining . "Darbuotoja (-s) " . $name . " " . $surname . ".";

            $expense->save();
        }
        return redirect()->route('home')->with('success', "Šio mėnesio darbo vietų išlaidos įvestos sėkmingai");
    }

    public function expense(Request $request)
    {
        $this->validateReceipt($request);
        $expense = new Expense();
        $expense->amount = $request->amount;
        $expense->reason = $request->reason;
        $expense->paid_at = $request->receiptDate;
        $expense->save();
        return redirect()->route('accounting.expense')->with('success', "Išlaida įvesta");
    }

    public function expenseForm()
    {
        return view('accounting.receiptForm', ["formRoute" => 'accounting.expense.save', 'title' => "Įvesti naują išlaidą"]);
    }

    public function income(Request $request)
    {
        $this->validateReceipt($request);
        $income = new Income();
        $income->amount = $request->amount;
        $income->reason = $request->reason;
        $income->received_at = $request->receiptDate;
        $income->save();
        return redirect()->route('accounting.income')->with('success', "Pajamos įvestos");
    }

    public function incomeForm()
    {
        return view('accounting.receiptForm', ["formRoute" => 'accounting.income.save', 'title' => "Įvesti naujas pajamas"]);
    }

    public function report(Request $request)
    {
        $monthMap = [
            1 => "Sausis",
            2 => "Vasaris",
            3 => "Kovas",
            4 => "Balandis",
            5 => "Gegužė",
            6 => "Birželis",
            7 => "Liepa",
            8 => "Rugpjūtis",
            9 => "Rugsėjis",
            10 => "Spalis",
            11 => "Lapkritis",
            12 => "Gruodis",
        ];

        $month = $request->month;

        if ($month == null || !preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = Carbon::now('Europe/Vilnius');
            $month = $month->subMonth();
        } else {
            $month = $month = Carbon::createFromFormat('Y-m', $month);
        }
        $monthStart = $month->copy()->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();

        $options = $this->getMonthOptions();

        $monthText = $month->year . " m. " . $monthMap[$month->month];

        $incomes = Income::whereBetween('received_at', [$monthStart, $monthEnd])
            ->orderBy('received_at', 'asc')
            ->get();
        $expenses = Expense::whereBetween('paid_at', [$monthStart, $monthEnd])
            ->orderBy('paid_at', 'asc')
            ->get();

        foreach ($incomes as $income) {
            if (substr($income->received_at, -8) === '00:00:00') {
                $income->received_at = substr($income->received_at, 0, -9);
            }
        }

        $incomeSum = Income::whereBetween('received_at', [$monthStart, $monthEnd])->sum('amount');
        $expenseSum = Expense::whereBetween('paid_at', [$monthStart, $monthEnd])->sum('amount');
        $profit = $incomeSum - $expenseSum;

        $types = ['income' => "Pajamos", 'expense' => "Išlaidos"];
        $values = ['income' => $incomes, 'expense' => $expenses];

        return view('accounting.report', [
            'month' => $monthText,
            'income' => $incomeSum,
            'expense' => $expenseSum,
            "diff" => $profit,
            'types' => $types,
            'values' => $values,
            'options' => $options
        ]);
    }

    protected function getMonthOptions()
    {
        $options = new Collection();
        $valuesI = Income::selectRaw('MONTH(received_at) AS month, YEAR(received_at) AS year')
            ->distinct()
            ->get();
        $valuesE = Expense::selectRaw('MONTH(paid_at) AS month, YEAR(paid_at) AS year')
            ->distinct()
            ->get();
        $options = $options->merge($valuesE)->merge($valuesI);

        $sortedOptions = $options->sortByDesc(function ($item) {
            return $item->year * 100 + $item->month;
        });

        $uniqueOpts = $sortedOptions->unique(function ($item) {
            return $item->month . '-' . $item->year;
        });
        return $uniqueOpts;
    }

    protected function validateReceipt(Request $request)
    {
        $request->validate([
            'receiptDate' => ['required', 'date', 'date_format:Y-m-d'],
            'reason' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'gt:0']
        ]);
    }
}
