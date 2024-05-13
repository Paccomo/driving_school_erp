<?php

namespace App\Http\Controllers\References;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\ExternalLink;
use App\Models\Link;
use Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class LinksController extends Controller
{
    public function list()
    {
        $links = ExternalLink::with('link')->get();
        return view('references.linksList', ['links' => $links, 'roleDirector' => Role::Director->value,]);
    }

    public function add()
    {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        return view('references.linksForm');
    }

    public function save(Request $request)
    {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $request->validate([
            'id' => ['integer', 'gt:0', 'exists:external_link,id'],
            'title' => ['required', 'string',],
            'source' => ['required', 'url']
        ]);

        if ($request->has('id')) {
            $link = Link::find($request->id);
        } else {
            $link = new Link();
        }

        $link->link = $request->source;
        $link->title = $request->title;
        $link->save();

        $extLink = ExternalLink::firstOrCreate([
            'id' => $link->id
        ]);
        $extLink->save();

        return Redirect::route('link.list')->with('success', 'Nuoroda sėkmingai išsaugota');
    }

    public function edit(Request $request)
    {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $validator = validator()->make([
            'id' => $request->id,
        ], [
            'id' => ['required', 'integer', 'gte:0', 'exists:external_link,id'],
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $link = ExternalLink::with('link')->find($request->id);
        return view('references.linksForm', ['link' => $link]);
    }

    public function destroy(Request $request) {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $extLink = ExternalLink::find($request->id);
        if ($extLink != null) {
            $extLink->delete();
            Link::destroy($request->id);
            return redirect()->route('link.list')->with('success', 'Nuoroda sėkmingai ištrinta!');
        }
        return redirect()->route('link.list')->with('fail', 'Nepavyko rasti norimos ištrinti nuorodos');
    }
}
