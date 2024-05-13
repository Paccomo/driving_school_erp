<?php

namespace App\Http\Controllers\References;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\TheoryPresentation;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Redirect;

class SlidesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:instructor,director');
    }

    public function list()
    {
        $slides = TheoryPresentation::with('link')->orderBy('order', 'asc')->get();
        return view('references.slidesList', ['slides' => $slides, 'roleDirector' => Role::Director->value,]);
    }

    public function add(Request $request)
    {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');
        return view('references.slidesForm');
    }

    public function index(Request $request) {
        $validator = validator()->make([
            'id' => $request->id,
        ], [
            'id' => ['required', 'integer', 'gte:0', 'exists:theory_presentation,id'],
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $link = Link::find($request->id);
        return response()->download(storage_path('app/public/slides/' . $link->link), null, [], null);
    }

    public function save(Request $request)
    {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $request->validate([
            'id' => ['integer', 'gt:0', 'exists:theory_presentation,id'],
            'title' => ['required', 'string'],
            'order' => ['required', 'integer', 'gt:0'],
            'slideFile' => ['file', 'mimes:pptx,pdf,ppt,odp'],
        ]);

        if (!isset($request->slideFile) && !isset($request->previousSlide))
            return redirect()->back()->withInput()->with('fail', 'Nepateiktas skaidrių failas');

        if ($request->has('id')) {
            $link = Link::find($request->id);
        } else {
            $link = new Link();
        }

        if ($request->hasFile('slideFile')) {
            if (isset($link->link) && $link->link != null && file_exists(storage_path('app/public/slides/' . $link->link))) {
                unlink(storage_path('app/public/slides/' . $link->link));
            }

            $slide = $request->file('slideFile');
            $slidesName = $slide->getClientOriginalName();

            if (file_exists(storage_path('app/public/slides/' . $slidesName))) {
                $extension = $slide->getClientOriginalExtension();
                $slidesName = uniqid() . '.' . $extension;
            }

            $slide->storeAs('public/slides/', $slidesName);
            $link->link = $slidesName;
        }

        $link->title = $request->title;
        $link->save();

        $slide = TheoryPresentation::firstOrCreate([
            'id' => $link->id
        ]);
        $slide->save();

        $slide = TheoryPresentation::find($link->id);
        $slide->order = $request->order;
        $slide->save();

        return Redirect::route('slides.list')->with('success', 'Skaidrės sėkmingai išsaugotos');
    }

    public function edit(Request $request)
    {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $validator = validator()->make([
            'id' => $request->id,
        ], [
            'id' => ['required', 'integer', 'gte:0', 'exists:theory_presentation,id'],
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $slide = TheoryPresentation::with('link')->find($request->id);
        $slideFile = $slide->link->link;
        return view('references.slidesForm', ['slide' => $slide, 'slideFile' => $slideFile]);
    }

    public function destroy(Request $request) {
        if (Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $slide = TheoryPresentation::find($request->id);
        if ($slide != null) {
            $slide->delete();
            $link = Link::find($request->id);
            if (isset($link->link) && $link->link != null && file_exists(storage_path('app/public/slides/' . $link->link))) {
                unlink(storage_path('app/public/slides/' . $link->link));
            }         
            return redirect()->route('slides.list')->with('success', 'Skaidrės sėkmingai ištrintos!');
        }
        return redirect()->route('slides.list')->with('fail', 'Nepavyko rasti norimų ištrinti skaidirių');
    }
}
