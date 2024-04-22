<?php

namespace App\Http\Controllers\Clients;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employee;
use Auth;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        return view('client.clientIndex', ['client' => $client,]);
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
