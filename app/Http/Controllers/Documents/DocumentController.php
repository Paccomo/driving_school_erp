<?php

namespace App\Http\Controllers\Documents;

use App\Constants\DocumentType;
use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Document;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Route;

class DocumentController extends Controller
{
    public function document() {
        if (Auth::user()->role != Role::Client->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $medCert = Document::where([
            ['fk_CLIENTid', Auth::user()->id],
            ['type', DocumentType::Medical->value],
            ['valid_until', '>', Carbon::today()]
        ])->first();

        $theory = Document::where([
            ['fk_CLIENTid', Auth::user()->id],
            ['type', DocumentType::Theory->value],
            ['valid_until', '>', Carbon::today()]
        ])->first();

        return view('document.document', ['medCert' => $medCert, 'theory' => $theory]);
    }

    public function add() {
        if (Auth::user()->role != Role::Client->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        if (Route::is('documents.addMed')) {
            $formTitle  = "Pridėti vairuotojo sveikatos pažymą";
            $docType = DocumentType::Medical->value;
        } else {
            $formTitle = 'Pridėti teorijos išlaikymo VĮ "REGITRA" pažymą';
            $docType = DocumentType::Theory->value;
        }
        $clientId = Auth::user()->id;
        return view('document.documentForm', ['formTitle' => $formTitle, 'docType' => $docType, 'clientId' => $clientId]);
    }

    public function save(Request $request) {
        $request->validate([
            'id' => ['required', 'integer', "gte:0", "exists:client,id"],
            'docType' => ['required', Rule::enum(DocumentType::class)],
            'date' => ['required', 'date'],
            'doc' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,pdf,bmp,tiff']
        ]);

        $doc = new Document();
        $doc->valid_until = $request->date;
        $doc->type = $request->docType;
        $doc->fk_CLIENTid = $request->id;

        $file = $request->file('doc');
        if ($file != null) {
            $fileName = $file->getClientOriginalName();
            if (file_exists(storage_path('app/documents/' . $fileName))) {
                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid() . '.' . $extension;
            }
            $file->storeAs('documents', $fileName);
            $doc->file = $fileName;
        }
        $doc->save();
        return redirect()->route('documents')->with('success', "Dokumentas sėkmingai pridėtas");
    }

    public function download(Request $request){
        $doc = Document::find($request->id);
        if (!$this->ensureDownloadAccess($doc->fk_CLIENTid) || $doc == null)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $path =  storage_path('app/documents/' . $doc->file);
        return response()->download($path, null, [], null);
    }

    public function destroy(Request $request) {
        $doc = Document::find($request->id);
        if (!$this->ensureNonClientAccess($doc->fk_CLIENTid) || $doc == null)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        unlink(storage_path('app/documents/' . $doc->file));
        $doc->delete();
        return redirect()->back()->with('success', "Dokumentas atmestas");
    }

    private function ensureDownloadAccess($fk) {
        if (Auth::user()->role == Role::Client->value && $fk == Auth::user()->id)
            return true;
        return $this->ensureNonClientAccess($fk);
    }

    private function ensureNonClientAccess($fk) {
        if (Auth::user()->role == Role::Administrator->value && Client::find($fk)->fk_BRANCHid == Auth::user()->employee->fk_BRANCHid)
            return true;
        if (Auth::user()->role == Role::Director->value)
            return true;
        return false;
    }
}
