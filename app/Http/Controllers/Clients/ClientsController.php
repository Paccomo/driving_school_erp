<?php

namespace App\Http\Controllers\Clients;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\BranchCategoricalCourse;
use App\Models\BranchCompetenceCourse;
use App\Models\Client;
use App\Models\Course;
use App\Models\Employee;
use App\Models\Income;
use App\Models\Payment;
use Auth;
use DB;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpOffice\PhpWord\TemplateProcessor;
use Route;

class ClientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrator,director');
    }

    public function list(Request $request)
    {
        $clients = collect();

        $clientsQuery = Client::with(['person', 'branch']);
        if (!Route::is('client.all')) {
            $clientsQuery->where('currently_studying', 1);
        }
        if (Auth::user()->role == Role::Administrator->value) {
            $clientsQuery->where('fk_BRANCHid', Employee::find(Auth::user()->id));
        } 
        $clientsQuery->chunk(100, function ($chunkClients) use (&$clients) {
            $clients = $clients->concat($chunkClients);
        });

        $clients = $this->paginate($clients);
        return view('client.clientList', ['clients' => $clients, 'roleDirector' => Role::Director->value,]);
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        if ($query == null || $query == '')
            return redirect()->route('client.list');

        $query = strtolower($query);
        $keywords = explode(' ', $query);
        $clients = collect();
        $clientsQuery = Client::with(['person', 'branch']);
        if (Auth::user()->role == Role::Administrator->value) {
            $clientsQuery->where('fk_BRANCHid', Employee::find(Auth::user()->id));
        }
        $clientsQuery->chunk(100, function ($chunkClients) use (&$clients, $keywords) {
            foreach ($chunkClients as $client) {
                $lowercaseName = strtolower($client->person->name);
                $lowercaseSurname = strtolower($client->person->surname);
                foreach ($keywords as $query) {
                    if (strpos($lowercaseName, $query) !== false || strpos($lowercaseSurname, $query) !== false) {
                        $clients->push($client);
                    }
                }
            }
        });
        $clients = $this->paginate($clients);
        return view('client.clientList', ['clients' => $clients, 'roleDirector' => Role::Director->value,]);
    }

    public function endStudy(Request $request)
    {
        $client = Client::find($request->id);
        if ($client == null)
            return redirect()->route('client.list')->with('fail', 'Mokinys nerastas');

        if (Auth::user()->role == Role::Administrator->value && Employee::find(Auth::user()->id)->fk_BRANCHid != $client->fk_BRANCHid)
            return redirect()->route('client.list')->with('fail', 'Neturite teisės redaguoti šį mokinį');

        $client->currently_studying = 0;
        $client->save();
        return redirect()->route('client.list')->with('success', 'Mokinio kursai pažymėti kaip "baigti"');
    }

    // TODO ensure lecture attendance info display
    // TODO ensure instructor display
    public function index(Request $request) { //TODO ensure document and contract functionality, once it is done
        $client = Client::with(['person', 'branch', 'account', 'course'])->find($request->id);
        if ($client == null)
            return redirect()->route('client.list')->with('fail', 'Mokinys nerastas');

        if (Auth::user()->role == Role::Administrator->value && Employee::find(Auth::user()->id)->fk_BRANCHid != $client->fk_BRANCHid)
            return redirect()->route('client.list')->with('fail', 'Neturite teisės matyti šį mokinį');

        $this->decypherPerson($client);

        if($client->fk_instructor != null) {
            $instructor = Employee::find($client->fk_instructor);
            $instructor = $instructor->person->name . " " . $instructor->person->surname;
        } else {
            $instructor = "--";
        }

        return view('client.clientIndex', ['client' => $client, 'instructor' => $instructor]);
    }

    public function TogglePracticalLessons(Request $request) {
        $client = Client::find($request->id);
        if ($client == null)
            return redirect()->route('client.list')->with('fail', 'Mokinys nerastas');

        if ($client->practical_lessons_permission == 1) {
            $client->practical_lessons_permission = 0;
        } else {
            $client->practical_lessons_permission = 1;
        }
        $client->save();
        return redirect()->back()->with('success', 'Mokinio vairavimo pamokų leidimo būsena pakeista');
    }

    public function grade(Request $request) {
        $client = Client::with('person')->find($request->id);
        if ($client == null)
            return redirect()->route('client.list')->with('fail', 'Mokinys nerastas');

        $client->fullName = $client->person->name . " " . $client->person->surname;
        return view('client.gradeForm', ['client' => $client,]);
    }

    public function saveGrade(Request $request) {
        $request->validate([
            'id' => ['integer', 'gt:0', 'exists:client,id'],
            'grade' => ['required', 'integer', 'between:1,10']
        ]);

        $client = Client::find($request->id);
        $client->theory_grade = $request->grade;
        $client->save();
        return redirect()->route('client.index', ['id' => $request->id])->with('success', 'Mokinio teorijos egzamino įvertis pakeistas');
    }

    public function payment(Request $request) {
        $client = Client::with('person')->find($request->id);
        if ($client == null)
            return redirect()->route('client.list')->with('fail', 'Mokinys nerastas');

        $client->fullName = $client->person->name . " " . $client->person->surname;
        return view('client.paymentForm', ['client' => $client,]);
    }

    public function savePayment(Request $request) {
        $request->validate([
            'id' => ['integer', 'gt:0', 'exists:client,id'],
            'pay' => ['required', 'numeric', 'gt:0'],
            'reason' => ['required', 'string']
        ]);

        $income = new Income();
        $income->amount = $request->pay;
        $income->reason = $request->reason;
        $income->received_at = DB::raw('NOW()');
        $income->save();

        $payment = new Payment();
        $payment->id = $income->id;
        $payment->fk_CLIENTid = $request->id;
        $payment->paid_in_office = true;
        $payment->save();

        $student = Client::with('person')->find($request->id);
        $student = $student->person->name . " " . $student->person->surname;

        return view('client.displayCheck', [
            'amount' => $request->pay,
            'reason' => $request->reason,
            'student' => $student,
            'date' => date('Y-m-d'),
            'payId' => $payment->id,
            'sumW'=> $request->sumW,
        ]);
    }

    public function driveDocForm(Request $request) {
        $client = Client::with('person')->find($request->id);
        if ($client == null)
            return redirect()->route('client.list')->with('fail', 'Mokinys nerastas');

        $client->fullName = $client->person->name . " " . $client->person->surname;
        return view('client.driveForm', ['client' => $client,]);
    }

    public function driveDoc(Request $request) {
        $request->validate([
            'id' => ['integer', 'gt:0', 'exists:client,id'],
            'gearbox' => ['required', 'string'],
            'carNum' => ['required', 'string'],
            'model' => ['required', 'string'],
        ]);

        $client = Client::with('person')->find($request->id);
        if ($client->fk_instructor == null)
            return redirect()->route('client.index', ['id' => $request->id])->with('fail', 'Mokinys neturi priskirto instruktoriaus');

        $inst = Employee::find($client->fk_instructor);
        $br = Branch::find($client->fk_BRANCHid);
        $course = Course::find($client->fk_COURSEid);
        $dir = Account::with('person')->where("role", Role::Director->value)->first();
        $dirName = substr($dir->person->name, 0, 1) . ". " . $dir->person->surname;

        $months = [
            1 => "sausio ",
            2 => "vasario ",
            3 => "kovo ",
            4 => "balandžio ",
            5 => "gegužės ",
            6 => "birželio ",
            7 => "liepos ",
            8 => "rugpjūčio ",
            9 => "rugsėjo ",
            10 => "spalio ",
            11 => "lapkričio ",
            12 => "gruodžio ",
        ];

        $templateProcessor = new TemplateProcessor(storage_path('app/drivingDocs/vl.docx'));
        $templateProcessor->setValue('year',  date('Y'));
        $templateProcessor->setValue('md',  $months[date('n')] . date('j'));
        $templateProcessor->setValue('dir',  $dirName);
        $templateProcessor->setValue('company_code',  "*********");
        $templateProcessor->setValue('company_name',  "Įmonės pavadinimas");
        $templateProcessor->setValue('br_addr',  $br->address);
        $templateProcessor->setValue('phoneNum',  $br->phone_number);
        $templateProcessor->setValue('email',  $br->email);
        $templateProcessor->setValue('st_name',  $client->person->name);
        $templateProcessor->setValue('st_surname',  $client->person->surname);
        $templateProcessor->setValue('inst_name',  $inst->person->name);
        $templateProcessor->setValue('inst_sur',  $inst->person->surname);
        $templateProcessor->setValue('vehicle_name',  $request->model);
        $templateProcessor->setValue('vehicle_nr',  $request->carNum);
        $templateProcessor->setValue('vehicle_transmission',  $request->gearbox);
        $templateProcessor->setValue('cat',  $course->name);

        $filename = storage_path('app/drivingDocs/' . uniqid() . ".docx" );
        $templateProcessor->saveAs($filename);
        try {
            return response()->download($filename, null, [], null);
        } catch (Exception $ignore) {}
    }

    public function receipt(Request $request) {
        $filePath =  storage_path('app/paymentChecks/ppk.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $number = $request->sum;
        $number = number_format((float) $request->sum, 2, '.', '');

        $sheet->setCellValue('A1', 'UAB "Įmonės pavadinimas"');
        $sheet->setCellValue('A31', 'UAB "Įmonės pavadinimas"');
        $sheet->setCellValue('E10', 'ERP');
        $sheet->setCellValue('E40', 'ERP');
        $sheet->setCellValue('G10', $request->payId);
        $sheet->setCellValue('G40', $request->payId);
        $sheet->setCellValue('B15', $request->reason);
        $sheet->setCellValue('B45', $request->reason);
        $sheet->setCellValue('C18', $request->sumW);
        $sheet->setCellValue('C48', $request->sumW);
        $sheet->setCellValue('K18', $number);
        $sheet->setCellValue('K48', $number);
        $sheet->setCellValue('E21', $request->student);
        $sheet->setCellValue('E51', $request->student);
        $sheet->setCellValue('E24', Auth::user()->person->name . " " . Auth::user()->person->surname);
        $sheet->setCellValue('E54', Auth::user()->person->name . " " . Auth::user()->person->surname);
        $sheet->setCellValue('A4', 'LT***-**');
        $sheet->setCellValue('A34', 'LT***-**');

        $filename = storage_path('app/paymentChecks/' . uniqid() . ".xlsx" );
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filename);
        return response()->download($filename, null, [], null);
    }

    public function edit(Request $request) {
        $client = Client::with(['person', 'account'])->find($request->id);
        if ($client == null)
            return redirect()->route('client.list')->with('fail', 'Mokinys nerastas');

        $this->decypherPerson($client);
        
        $fullAddr = explode(", ", $client->person->address);
        $client->person->address = $fullAddr[0];
        $client->person->city = $fullAddr[1];
        
        $branchIds = BranchCompetenceCourse::where('fk_COMPETENCE_COURSEid', $client->fk_COURSEid)->pluck('fk_BRANCHid')->toArray();
        if (empty($branchIds))
            $branchIds = BranchcategoricalCourse::where('fk_CATEGORICAL_COURSEid', $client->fk_COURSEid)->pluck('fk_BRANCHid')->toArray();
        $branches = Branch::whereIn('id', $branchIds)->get();

        return view('client.clientForm', [
            'client' => $client,
            'roleDirector' => Role::Director->value,
            'branches' => $branches
        ]);
    }

    public function save(Request $request) {
        $request->validate([
            'id' => ['required', 'integer', 'gt:0', 'exists:client,id'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'name' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'surname' => ['required', 'regex:/^[\p{L} -]+$/u'],
            'pid' => ['required', 'integer', 'digits:11'],
            'address' => ['required', 'regex:/^[\p{L}. \-\d]+$/u'],
            'city' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'phoneNum' => ['required', 'regex:/^(8|\+370)\s?6\s?\d(?:\s?\d){6}$/'],
            'branch' => ['required', 'integer',  'gt:0', 'exists:branch,id'],
            'course' => ['required', 'integer',  'gt:0', 'exists:course,id'],
        ]);

        $branchCatCourses = BranchCategoricalCourse::where([
            ['fk_CATEGORICAL_COURSEid', $request->course],
            ['fk_BRANCHid', $request->branch]
        ])->get();
        $branchCompCourses = BranchCompetenceCourse::where([
            ['fk_COMPETENCE_COURSEid', $request->course],
            ['fk_BRANCHid', $request->branch]
        ])->get();
        if ($branchCatCourses->isEmpty() && $branchCompCourses->isEmpty())
            return redirect()->route('client.list')->with('fail', 'Neleistinas kliento kursas arba filialas');
        
        $client = Client::with(['account', 'person'])->find($request->id);
        $client->account->email = $request->email;
        $client->account->save();

        $client->person->name = encrypt($request->name);
        $client->person->surname = encrypt($request->surname);
        $client->person->pid = encrypt($request->pid);
        $client->person->address = encrypt($request->address . ", " . $request->city);
        $client->person->phone_number = encrypt($request->phoneNum);
        $client->person->save();

        $client->fk_BRANCHid = $request->branch;
        $client->save();
        return redirect()->route('client.list')->with('success', 'Mokinio informacija atnaujinta');
    }

    private function paginate(Collection $clients): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $clients->forPage(request()->query('page', 1), 30),
            $clients->count(),
            30,
            request()->query('page', 1),
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    private function decypherPerson(Client &$person): void {
        $person->person->pid = decrypt($person->person->pid);
        $person->person->address = decrypt($person->person->address);
        $person->person->phone_number = decrypt($person->person->phone_number);
    }
}
