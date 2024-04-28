<?php

namespace App\Http\Controllers\Contract;

use App\Constants\ContractType;
use App\Constants\RequestStatus;
use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ContractRequest;
use App\Models\Course;
use App\Models\Employee;
use App\Models\GuestContractRequest;
use Auth;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Route;

class ContractsController extends Controller
{
    public function guestRequest(Request $request) {
        $request->validate([
            'course' => ['required', 'integer',  'gt:0', 'exists:course,id'],
            'branch' => ['required', 'integer',  'gt:0', 'exists:branch,id'],
            'name' => ['required', 'regex:/^[\p{L} ]+$/u'],
            'surname' => ['required', 'regex:/^[\p{L} -]+$/u'],
            'phoneNum' => ['required', 'regex:/^(8|\+370)\s?6\s?\d(?:\s?\d){6}$/'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'comment' => ['nullable', 'string']
        ]);

        $contractR = new ContractRequest();
        $contractR->requested_on = new DateTime();
        $contractR->status = RequestStatus::Unconfirmed->value;
        $contractR->type = ContractType::TeachingContract->value;
        $contractR->fk_COURSEid = $request->course;
        $contractR->fk_BRANCHid = $request->branch;

        if ($request->improvement != null)
            $contractR->type = ContractType::ImprovementContract->value;
        else
            $contractR->type = ContractType::TeachingContract->value;

        $comment = "";
        if ($request->noTheory != null)
            $comment .= "--EKSTERNU--\n";
        if ($request->comment != null) 
            $comment .= $request->comment;
        $contractR->comment = $comment;
        $contractR->save();

        $guestReq = new GuestContractRequest();
        $guestReq->name = $request->name;
        $guestReq->id = $contractR->id;
        $guestReq->surname = $request->surname;
        $guestReq->phone_number = $request->phoneNum;
        $guestReq->email = $request->email;
        $guestReq->save();
        return redirect()->route('course.list')->with('success', "Užklausa užsirašymui į mokymus pateikta sėkmingai!");
    }

    public function list() {
        $this->authCheck();

        $contracts = collect();
        if (Route::is('contract.list'))
            $filter = RequestStatus::Unconfirmed->value;
        else if (Route::is('contract.accepted'))
            $filter = RequestStatus::Approved->value;
        else if (Route::is('contract.denied'))
            $filter = RequestStatus::Denied->value;
        else
            $filter = null;

        $contractReqQuery = ContractRequest::with(['guestReq', 'clientReq', 'contract']);
        if ($filter != null)
            $contractReqQuery->where('status', $filter);
        if (Auth::user()->role == Role::Administrator->value)
            $contractReqQuery->where('fk_BRANCHid', Employee::find(Auth::user()->id)->fk_BRANCHid);
        $contractReqQuery->orderBy('requested_on', 'desc')
            ->chunk(100, function ($chunkContracts) use (&$contracts) {
                $contracts = $contracts->concat($chunkContracts);
            });

        $contracts = $this->paginate($contracts);
        return view('contract.list', ['contracts' => $contracts, 'types' => $this->getRequestTypeNames(),]);
    }

    public function index(Request $request) {
        $this->authCheck();

        $cr = ContractRequest::with(['guestReq', 'clientReq', 'contract'])->find($request->id);
        if ($cr == null) 
            return redirect()->route('contract.list')->with('fail', 'Sutarties užklausa nerasta');

        $branch = Branch::find($cr->fk_BRANCHid);
        $course = Course::find($cr->fk_COURSEid);
        return view('contract.contractIndex', [
            'types' => $this->getRequestTypeNames(),
            'contract' => $cr,
            'branch' => $branch,
            'course' => $course,
        ]);
    }

    public function approve(Request $request) {
        $this->authCheck();

        $cr = ContractRequest::with(['guestReq', 'clientReq', 'contract'])->find($request->id);
        if ($cr == null) 
            return redirect()->route('contract.list')->with('fail', 'Sutarties užklausa nerasta');

        $cr->status = RequestStatus::Approved->value;
        $cr->save();
        //TODO send email
        return redirect()->back()->with('success', "Užklausa priimta");
    }

    public function deny(Request $request) {
        $this->authCheck();

        $cr = ContractRequest::with(['guestReq', 'clientReq', 'contract'])->find($request->id);
        if ($cr == null) 
            return redirect()->route('contract.list')->with('fail', 'Sutarties užklausa nerasta');

        $cr->status = RequestStatus::Denied->value;
        $cr->save();
        //TODO send email
        return redirect()->back()->with('success', "Užklausa atmesta");
    }

    public function add(Request $request) {
        $this->authCheck();

        $cr = ContractRequest::with(['guestReq', 'clientReq', 'contract'])->find($request->id);
        if ($cr == null) 
            return redirect()->route('contract.list')->with('fail', 'Sutarties užklausa nerasta');

        $client = null;
        if ($cr->guestReq != null) {
            $name = $cr->guestReq->name;
            $surname = $cr->guestReq->surname;
            $clients = Client::with('person')->where('fk_BRANCHid', $cr->fk_BRANCHid)
                ->whereDoesntHave('contract')->get();
            
            foreach($clients as $c) {
                if ($c->person->name == $name && $c->person->surname == $surname){
                    $client = $c;
                    break;
                }
            }

            if ($client == null) {
                return redirect()->route('contract.list')->with('fail', "Prieš įkeliant sutartį reikia priregistruoti klientą");
            }
        } else {
            $client = $cr->clientReq->client;
        }
        return view('contract.contractRequestForm', ['client' => $client, 'contract' => $cr]);
    }

    public function addWithoutRequest() {
        $this->authCheck();

        $clients = Client::with('person')->whereDoesntHave('contract');
        if (Auth::user()->role == Role::Administrator->value) {
            $clients->where('fk_BRANCHid', Employee::find(Auth::user()->id)->fk_BRANCHid);
        }
        $clients = $clients->get();
        return view('contract.contractForm', ['clients' => $clients,]);
    }

    public function save(Request $request) {
        $this->authCheck();
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf'],
            'client' => ['required', 'integer', "gte:0", "exists:client,id"],
            'id' => ['required', 'integer', "gte:0", "exists:contract_request,id"],
        ]);

        $contract = new Contract();
        $contract->fk_CLIENTid = $request->client;
        $contract->fk_CONTRACT_REQUESTid = $request->id;

        $file = $request->file('file');
        if ($file != null) {
            $fileName = $file->getClientOriginalName();
            if (file_exists(storage_path('app/contracts/' . $fileName))) {
                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid() . '.' . $extension;
            }
            $file->storeAs('contracts', $fileName);
            $contract->link = $fileName;
        }
        $contract->save();
        return redirect()->route('contract.list')->with('success', "Sutartis įkelta sėkmingai");
    }

    public function saveWithoutRequest(Request $request) {
        $this->authCheck();
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf'],
            'client' => ['required', 'integer', "gte:0", "exists:client,id"],
            'title' => ['required', 'string'],
        ]);

        $contract = new Contract();
        $contract->fk_CLIENTid = $request->client;
        $contract->name = $request->title;

        $file = $request->file('file');
        if ($file != null) {
            $fileName = $file->getClientOriginalName();
            if (file_exists(storage_path('app/contracts/' . $fileName))) {
                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid() . '.' . $extension;
            }
            $file->storeAs('contracts', $fileName);
            $contract->link = $fileName;
        }
        $contract->save();
        return redirect()->route('contract.list')->with('success', "Sutartis įkelta sėkmingai");
    }

    public function download(Request $request){
        $this->authCheck();

        $contract = Contract::find($request->id);
        if ($contract == null) 
            return redirect()->route('contract.list')->with('fail', 'Sutarties užklausa nerasta');

        $path =  storage_path('app/contracts/' . $contract->link);
        return response()->download($path, null, [], null);
    }

    private function authCheck() {
        if (Auth::user()->role != Role::Director->value && Auth::user()->role != Role::Administrator->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');
    }

    private function paginate(Collection $contracts): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $contracts->forPage(request()->query('page', 1), 30),
            $contracts->count(),
            30,
            request()->query('page', 1),
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    private function getRequestTypeNames() {
        return [
            ContractType::TeachingContract->value => "Kursų sutarties užklausa",
            ContractType::ImprovementContract->value => "Tobulinimosi sutarties užklausa",
            ContractType::Termination->value => "Sutarties nutraukimo užklausa",
            ContractType::Extension->value => "Sutarties pratęsimo užklausa",
        ];
    }
}
