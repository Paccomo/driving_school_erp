@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        {{ isset($branch) ? 'Redaguoti filialą' : 'Naujas filialas' }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('branch.save') }}">
                            @csrf
                            @if (isset($branch))
                                @method('PUT')
                                <input id="id" type="hidden" class="form-control" name="id"
                                    value="{{ $branch->id }}">
                            @endif

                            <div class="row mb-3">
                                <label for="address"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Filialo adresas') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="address" type="text"
                                        class="form-control @error('address') is-invalid @enderror" name="address"
                                        value="{{ isset($branch) ? $branch->address : old('address') }}" required>

                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="city"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Miestas') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="city" type="text"
                                        class="form-control @error('city') is-invalid @enderror" name="city"
                                        value="{{ isset($branch) ? $branch->city : old('city') }}" required>

                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phoneNum"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Kontaktinis tel. nr.') }}</label>

                                <div class="col-md-6">
                                    <input id="phoneNum" type="tel"
                                        class="form-control @error('phoneNum') is-invalid @enderror" name="phoneNum"
                                        value="{{ isset($branch) ? $branch->phone_number : old('phoneNum') }}">

                                    @error('phoneNum')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Kontaktinis el. paštas') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ isset($branch) ? $branch->email : old('email') }}">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="image"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Filialo nuotrauka') }}</label>

                                <div class="col-md-6">
                                    @if (isset($branch) && $branch->image)
                                        <img src="{{ asset('path/to/your/images/' . $branch->image) }}" alt="Branch Image"
                                            style="max-width: 200px;">
                                        <p>Current Image</p>
                                    @endif

                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*">

                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="groupSize"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Maksimalus mokymo grupės dydis') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="groupSize" type="number" step="1" min="1"
                                        class="form-control @error('groupSize') is-invalid @enderror" required
                                        value="{{ isset($branch) ? $branch->max_group_size : old('groupSize') }}"
                                        name="groupSize">

                                    @error('groupSize')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Trumpas filialo aprašymas') }}</label>

                                <div class="col-md-6">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        value="{{ isset($branch) ? $branch->description : old('description') }}" rows="5"></textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="monday"
                                    class="col-md-3 col-form-label align-self-end text-md-end">{{ __('Pirmadienis') }}</label>
                                <div class="col-md-3">
                                    <label for="monday_open">{{ __('Atidaroma') }}</label>
                                    <input type="time" id="monday_open" name="monday_open"
                                        value="{{ isset($branch) ? $branch->monday_open : old('monday_open') }}"
                                        class="form-control @error('monday_open') is-invalid @enderror">
                                    @error('monday_open')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="monday_close">{{ __('Uždaroma') }}</label>
                                    <input type="time" id="monday_close" name="monday_close"
                                        value="{{ isset($branch) ? $branch->monday_close : old('monday_close') }}"
                                        class="form-control @error('monday_close') is-invalid @enderror">
                                    @error('monday_close')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="monday_lunch">{{ __('Pietų pertrauka') }}</label>
                                    <input type="time" id="monday_lunch" name="monday_lunch"
                                        value="{{ isset($branch) ? $branch->monday_lunch : old('monday_lunch') }}"
                                        class="form-control @error('monday_lunch') is-invalid @enderror">
                                    @error('monday_lunch')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tuesday"
                                    class="col-md-3 col-form-label align-self-end text-md-end">{{ __('Antradienis') }}</label>
                                <div class="col-md-3">
                                    <label for="tuesday_open">{{ __('Atidaroma') }}</label>
                                    <input type="time" id="tuesday_open" name="tuesday_open"
                                        value="{{ isset($branch) ? $branch->tuesday_open : old('tuesday_open') }}"
                                        class="form-control @error('tuesday_open') is-invalid @enderror">
                                    @error('tuesday_open')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="tuesday_close">{{ __('Uždaroma') }}</label>
                                    <input type="time" id="tuesday_close" name="tuesday_close"
                                        value="{{ isset($branch) ? $branch->tuesday_close : old('tuesday_close') }}"
                                        class="form-control @error('tuesday_close') is-invalid @enderror">
                                    @error('tuesday_close')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="tuesday_lunch">{{ __('Pietų pertrauka') }}</label>
                                    <input type="time" id="tuesday_lunch" name="tuesday_lunch"
                                        value="{{ isset($branch) ? $branch->tuesday_lunch : old('tuesday_lunch') }}"
                                        class="form-control @error('tuesday_lunch') is-invalid @enderror">
                                    @error('tuesday_lunch')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="wednesday"
                                    class="col-md-3 col-form-label align-self-end text-md-end">{{ __('Trečiadienis') }}</label>
                                <div class="col-md-3">
                                    <label for="wednesday_open">{{ __('Atidaroma') }}</label>
                                    <input type="time" id="wednesday_open" name="wednesday_open"
                                        value="{{ isset($branch) ? $branch->wednesday_open : old('wednesday_open') }}"
                                        class="form-control @error('wednesday_open') is-invalid @enderror">
                                    @error('wednesday_open')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="wednesday_close">{{ __('Uždaroma') }}</label>
                                    <input type="time" id="wednesday_close" name="wednesday_close"
                                        value="{{ isset($branch) ? $branch->wednesday_close : old('wednesday_close') }}"
                                        class="form-control @error('wednesday_close') is-invalid @enderror">
                                    @error('wednesday_close')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="wednesday_lunch">{{ __('Pietų pertrauka') }}</label>
                                    <input type="time" id="wednesday_lunch" name="wednesday_lunch"
                                        value="{{ isset($branch) ? $branch->wednesday_lunch : old('wednesday_lunch') }}"
                                        class="form-control @error('wednesday_lunch') is-invalid @enderror">
                                    @error('wednesday_lunch')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="thursday"
                                    class="col-md-3 col-form-label align-self-end text-md-end">{{ __('Ketvirtadienis') }}</label>
                                <div class="col-md-3">
                                    <label for="thursday_open">{{ __('Atidaroma') }}</label>
                                    <input type="time" id="thursday_open" name="thursday_open"
                                        value="{{ isset($branch) ? $branch->thursday_open : old('thursday_open') }}"
                                        class="form-control @error('thursday_open') is-invalid @enderror">
                                    @error('thursday_open')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="thursday_close">{{ __('Uždaroma') }}</label>
                                    <input type="time" id="thursday_close" name="thursday_close"
                                        value="{{ isset($branch) ? $branch->thursday_close : old('thursday_close') }}"
                                        class="form-control @error('thursday_close') is-invalid @enderror">
                                    @error('thursday_close')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="thursday_lunch">{{ __('Pietų pertrauka') }}</label>
                                    <input type="time" id="thursday_lunch" name="thursday_lunch"
                                        value="{{ isset($branch) ? $branch->thursday_lunch : old('thursday_lunch') }}"
                                        class="form-control @error('thursday_lunch') is-invalid @enderror">
                                    @error('thursday_lunch')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="friday"
                                    class="col-md-3 col-form-label align-self-end text-md-end">{{ __('Penktadienis') }}</label>
                                <div class="col-md-3">
                                    <label for="friday_open">{{ __('Atidaroma') }}</label>
                                    <input type="time" id="friday_open" name="friday_open"
                                        value="{{ isset($branch) ? $branch->friday_open : old('friday_open') }}"
                                        class="form-control @error('friday_open') is-invalid @enderror">
                                    @error('friday_open')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="friday_close">{{ __('Uždaroma') }}</label>
                                    <input type="time" id="friday_close" name="friday_close"
                                        value="{{ isset($branch) ? $branch->friday_close : old('friday_close') }}"
                                        class="form-control @error('friday_close') is-invalid @enderror">
                                    @error('friday_close')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="friday_lunch">{{ __('Pietų pertrauka') }}</label>
                                    <input type="time" id="friday_lunch" name="friday_lunch"
                                        value="{{ isset($branch) ? $branch->friday_lunch : old('friday_lunch') }}"
                                        class="form-control @error('friday_lunch') is-invalid @enderror">
                                    @error('friday_lunch')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="saturday"
                                    class="col-md-3 col-form-label align-self-end text-md-end">{{ __('Šeštadienis') }}</label>
                                <div class="col-md-3">
                                    <label for="saturday_open">{{ __('Atidaroma') }}</label>
                                    <input type="time" id="saturday_open" name="saturday_open"
                                        value="{{ isset($branch) ? $branch->saturday_open : old('saturday_open') }}"
                                        class="form-control @error('saturday_open') is-invalid @enderror">
                                    @error('saturday_open')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="saturday_close">{{ __('Uždaroma') }}</label>
                                    <input type="time" id="saturday_close" name="saturday_close"
                                        value="{{ isset($branch) ? $branch->saturday_close : old('saturday_close') }}"
                                        class="form-control @error('saturday_close') is-invalid @enderror">
                                    @error('saturday_close')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="saturday_lunch">{{ __('Pietų pertrauka') }}</label>
                                    <input type="time" id="saturday_lunch" name="saturday_lunch"
                                        value="{{ isset($branch) ? $branch->saturday_lunch : old('saturday_lunch') }}"
                                        class="form-control @error('saturday_lunch') is-invalid @enderror">
                                    @error('saturday_lunch')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="sunday"
                                    class="col-md-3 col-form-label align-self-end text-md-end">{{ __('Sekmadienis') }}</label>
                                <div class="col-md-3">
                                    <label for="sunday_open">{{ __('Atidaroma') }}</label>
                                    <input type="time" id="sunday_open" name="sunday_open"
                                        value="{{ isset($branch) ? $branch->sunday_open : old('sunday_open') }}"
                                        class="form-control @error('sunday_open') is-invalid @enderror">
                                    @error('sunday_open')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="sunday_close">{{ __('Uždaroma') }}</label>
                                    <input type="time" id="sunday_close" name="sunday_close"
                                        value="{{ isset($branch) ? $branch->sunday_close : old('sunday_close') }}"
                                        class="form-control @error('sunday_close') is-invalid @enderror">
                                    @error('sunday_close')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="sunday_lunch">{{ __('Pietų pertrauka') }}</label>
                                    <input type="time" id="sunday_lunch" name="sunday_lunch"
                                        value="{{ isset($branch) ? $branch->sunday_lunch : old('sunday_lunch') }}"
                                        class="form-control @error('sunday_lunch') is-invalid @enderror">
                                    @error('sunday_lunch')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="courses"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Vykdomi kursai') }}</label>
                                <div class="col-md-6">
                                    @foreach ($catCourses as $course)
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                id="course{{ $course->id }}" name="courses[]"
                                                value="{{ $course->id }}" role="switch"
                                                @if (isset($branch) && in_array($course->id, $branchCourses)) checked @endif>
                                            <label class="form-check-label"
                                                for="course{{ $course->id }}">{{ $course->name }}</label>
                                        </div>
                                    @endforeach
                                    @foreach ($compCourses as $course)
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                id="course{{ $course->id }}" name="courses[]"
                                                value="{{ $course->id }}" role="switch"
                                                @if (isset($branch) && in_array($course->id, $branchCourses)) checked @endif>
                                            <label class="form-check-label"
                                                for="course{{ $course->id }}">{{ $course->name }}</label>
                                        </div>
                                    @endforeach
                                    @error('courses')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Išsaugoti') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
