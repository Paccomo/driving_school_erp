@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        {{ isset($branch) ? 'Redaguoti filialą' : 'Naujas filialas' }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('branch.save') }}" enctype="multipart/form-data">
                            @csrf
                            @if (isset($branch))
                                @method('PUT')
                                <input id="id" type="hidden" class="form-control" name="id"
                                    value="{{ $branch->id }}">
                            @endif

                            <div class="row mb-3">
                                <label for="address"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Filialo adresas') }}<span
                                        class="text-danger">*</span></label>

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
                                <label for="city" class="col-md-3 col-form-label text-md-end">{{ __('Miestas') }}<span
                                        class="text-danger">*</span></label>

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
                                        <img src="{{ $branch->image }}" alt="Filialo nuotrauka" style="max-width: 200px;">
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
                                    class="col-md-3 col-form-label text-md-end">{{ __('Maksimalus mokymo grupės dydis') }}<span
                                        class="text-danger">*</span></label>

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
                                        rows="5">{{ isset($branch) ? $branch->description : old('description') }}</textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            @foreach ($weekdays as $weekday)
                                <div class="row mb-3">
                                    <label for="{{ $weekday }}"
                                        class="col-md-3 col-form-label align-self-end text-md-end">@lang('messages.' . $weekday)</label>

                                    @foreach ($types as $type)
                                        <div class="col-md-3">
                                            <label for="{{ $weekday . '_' . $type }}">@lang('messages.' . $type)</label>
                                            <input type="time" id="{{ $weekday . '_' . $type }}"
                                                name="{{ $weekday . '_' . $type }}"
                                                value="{{ isset($branch) ? $branch->{$weekday . '_' . $type} : old($weekday . '_' . $type) }}"
                                                class="form-control @error($weekday . '_' . $type) is-invalid @enderror">

                                            @error($weekday . '_' . $type)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="row mb-3">
                                <label for="courses"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Vykdomi kursai') }}</label>
                                <div class="col-md-6">
                                    @foreach ($catCourses as $course)
                                        <div class="row align-items-center mb-2">
                                            <div class="col">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="course{{ $course->id }}" name="courses[]"
                                                        value="{{ $course->id }}" role="switch"
                                                        @if (isset($branch) && in_array($course->id, $branchCourses)) checked @endif>
                                                    <label class="form-check-label"
                                                        for="course{{ $course->id }}">{{ $course->name }}</label>
                                                </div>
                                            </div>
                                            <div class="col additional-inputs">
                                                <label
                                                    for="course{{ $course->id }}_theory">{{ __('Teorijos kaina') }}</label>
                                                <input type="number" min="1" step="0.01"
                                                    id="course{{ $course->id }}_theory"
                                                    name="course{{ $course->id }}_theory"
                                                    value="{{ isset($branch) && isset($coursePrices[$course->id]['theory']) ? $coursePrices[$course->id]['theory'] : old('course' . $course->id . '_theory') }}"
                                                    class="form-control form-control-sm  @error('course' . $course->id . '_theory') is-invalid @enderror">
                                            </div>
                                            <div class="col additional-inputs">
                                                <label
                                                    for="course{{ $course->id }}_practice">{{ __('Praktikos kaina') }}</label>
                                                <input type="number" min="1" step="0.01"
                                                    id="course{{ $course->id }}_practice"
                                                    name="course{{ $course->id }}_practice"
                                                    value="{{ isset($branch) && isset($coursePrices[$course->id]['practice']) ? $coursePrices[$course->id]['practice'] : old('course' . $course->id . '_practice') }}"
                                                    class="form-control form-control-sm  @error('course' . $course->id . '_practice') is-invalid @enderror">
                                            </div>
                                            <div class="col additional-inputs">
                                                <label
                                                    for="course{{ $course->id }}_lesson">{{ __('Papildomas važiavimas') }}</label>
                                                <input type="number" min="1" step="0.01"
                                                    id="course{{ $course->id }}_theory"
                                                    name="course{{ $course->id }}_lesson"
                                                    value="{{ isset($branch) && isset($coursePrices[$course->id]['lesson']) ? $coursePrices[$course->id]['lesson'] : old('course' . $course->id . '_lesson') }}"
                                                    class="form-control form-control-sm  @error('course' . $course->id . '_lesson') is-invalid @enderror">
                                            </div>
                                        </div>
                                    @endforeach
                                    @foreach ($compCourses as $course)
                                        <div class="row align-items-center mb-2">
                                            <div class="col">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="course{{ $course->id }}" name="courses[]"
                                                        value="{{ $course->id }}" role="switch"
                                                        @if (isset($branch) && in_array($course->id, $branchCourses)) checked @endif>
                                                    <label class="form-check-label"
                                                        for="course{{ $course->id }}">{{ $course->name }}</label>
                                                </div>
                                            </div>
                                            <div class="col additional-inputs">
                                                <label for="course{{ $course->id }}_lesson">{{ __('Kaina') }}</label>
                                                <input type="number" min="1" step="0.01"
                                                    id="course{{ $course->id }}_price"
                                                    name="course{{ $course->id }}_price"
                                                    value="{{ isset($branch) && isset($coursePrices[$course->id]['price']) ? $coursePrices[$course->id]['price'] : old('course' . $course->id . '_price') }}"
                                                    class="form-control form-control-sm  @error('course' . $course->id . '_price') is-invalid @enderror">
                                            </div>
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
    <script>
        var checkboxes = document.querySelectorAll('.form-check-input');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                courseInputsToggle(checkbox);
            });
        });

        function courseInputsToggle(checkbox) {
            var row = checkbox.closest('.row');
            var additionalInputs = row.querySelectorAll('.additional-inputs');
            additionalInputs.forEach(function(input) {
                input.style.display = checkbox.checked ? 'block' : 'none';
            });
        }

        checkboxes.forEach(function(checkbox) {
            courseInputsToggle(checkbox);
        });
    </script>
@endsection
