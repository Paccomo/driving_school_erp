@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
    @if (session('fail'))
        <div class="alert alert-danger auto-dismiss">
            {{ session('fail') }}
        </div>
    @endif

    <div style="padding: 0.2cm 0.4cm 1cm 0.4cm" class="d-flex align-items-left">
        <h1 style="margin-right: 0.4cm">Naudingi vaizdo įrašai</h1>
        @if (Auth()->check() && Auth::user()->role == $roleDirector)
            <a style="margin-right: 1.5rem;" href="{{ route('video.add') }}" class="btn btn-primary btn-sm h-25 fs-6">Pridėti vaizdo įrašą</a>
        @endif
    </div>

    <div class="accordion accordion-flush" id="videoList">
        @foreach ($videos as $video)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="video-{{ $video->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#video-{{ $video->id }}-prices" aria-expanded="false"
                            aria-controls="video-{{ $video->id }}-prices">
                            {{ $video->link->title }}
                        </button>
                    </h2>
                    <div id="video-{{ $video->id }}-prices" class="accordion-collapse collapse"
                        aria-labelledby="video-{{ $video->id }}" data-bs-parent="#videoList">
                        <div class="accordion-body">
                            @if ($video->isURL)
                                <iframe width="560" height="315" src="{{$video->link->link}}" frameborder="0" allowfullscreen></iframe>
                            @else
                                <video width="560" height="315" controls>
                                    <source src="{{$video->link->link}}" type="video/mp4">
                                </video>                            
                            @endif
                        </div>
                    </div>
                </div>
        @endforeach
    </div>
@endsection