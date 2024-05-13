<?php

namespace App\Http\Controllers\References;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\EducationalVideo;
use App\Models\Link;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VideosController extends Controller
{
    public function list() {
        $videos = EducationalVideo::with('link')->get();
        foreach ($videos as $video) {
            if (strpos($video->link->link, 'http://') === 0 || strpos($video->link->link, 'https://') === 0) {
                $video->isURL = true;
            } else {
                $video->isURL = false;
                $video->link->link = url('storage/videos/' . $video->link->link);
            }
        }
        return view('references.videosList', ['videos' => $videos, 'roleDirector' => Role::Director->value,]);
    }

    public function add(Request $request)
    {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        if ($request->route()->named('video.add')) {
            return view('references.videosForm');
        } elseif ($request->route()->named('video.addLink')) {
            return view('references.videosLinkForm');
        }

        return redirect()->route('video.list')->with('fail', 'Pateikta klaidinga nuoro');
    }

    public function save(Request $request)
    {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $request->validate([
            'id' => ['integer', 'gt:0', 'exists:educational_video,id'],
            'title' => ['required', 'string',],
            'source' => ['url'],
            'video_file' => ['file', 'mimes:mp4,mov,avi,wmv'],
        ]);

        if (!isset($request->video_file) && !isset($request->source) && !isset($request->previousVideo))
            return redirect()->back()->withInput()->with('fail', 'Nepateiktas vaizdo įrašas');

        if ($request->has('id')) {
            $link = Link::find($request->id);
        } else {
            $link = new Link();
        }

        if ($request->hasFile('video_file')) {
            if (isset($link->link) && file_exists(storage_path('app/public/videos/' . $link->link))) {
                unlink(storage_path('app/public/videos/' . $link->link));
            }

            $video = $request->file('video_file');
            $videoName = $video->getClientOriginalName();

            if (file_exists(storage_path('app/public/videos/' . $videoName))) {
                $extension = $video->getClientOriginalExtension();
                $videoName = uniqid() . '.' . $extension;
            }

            $video->storeAs('public/videos/', $videoName);
            $link->link = $videoName;
        } else if (isset($request->source)) {
            $link->link = $request->source;
        }

        $link->title = $request->title;
        $link->save();

        $video = EducationalVideo::firstOrCreate([
            'id' => $link->id
        ]);
        $video->save();

        return Redirect::route('video.list')->with('success', 'Vaizdo įrašas sėkmingai išsaugotas');
    }

    public function edit(Request $request)
    {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $validator = validator()->make([
            'id' => $request->id,
        ], [
            'id' => ['required', 'integer', 'gte:0', 'exists:educational_video,id'],
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $video = EducationalVideo::with('link')->find($request->id);
        if (strpos($video->link->link, 'http://') === 0 || strpos($video->link->link, 'https://') === 0) {
            return view('references.videosLinkForm', ['video' => $video]);
        }

        $videoFile = url('storage/videos/' . $video->link->link);
        return view('references.videosForm', ['video' => $video, 'videoFile' => $videoFile]);
    }

    public function destroy(Request $request) {
        if (Auth::guest() || Auth::user()->role != Role::Director->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $video = EducationalVideo::find($request->id);
        if ($video != null) {
            $video->delete();
            $link = Link::find($request->id);
            if (!(strpos($video->link->link, 'http://') === 0 || strpos($video->link->link, 'https://') === 0)) {
                if (file_exists(storage_path('app/public/videos/' . $link->link)) && is_file(storage_path('app/public/videos/' . $link->link))) {
                    unlink(storage_path('app/public/videos/' . $link->link));
                }
            }            
            return redirect()->route('video.list')->with('success', 'Vaizdo irašas sėkmingai ištrintas!');
        }
        return redirect()->route('video.list')->with('fail', 'Nepavyko rasti norimo ištrinti vaizdo įrašo ');
    }
}
