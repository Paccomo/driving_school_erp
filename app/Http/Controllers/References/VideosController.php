<?php

namespace App\Http\Controllers\References;

use App\Constants\Role;
use App\Http\Controllers\Controller;
use App\Models\EducationalVideo;
use Illuminate\Http\Request;

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
}
