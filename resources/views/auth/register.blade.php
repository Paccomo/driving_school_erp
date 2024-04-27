@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-white bg-secondary">{{ __('Naujo naudotojo registracija') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}"  enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Vardas') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name" required
                                        autocomplete="given-name">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="surname"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Pavardė') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="surname" type="text"
                                        class="form-control @error('surname') is-invalid @enderror" name="surname" required
                                        autocomplete="family-name">

                                    @error('surname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('El. paštas') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="pid"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Asmens kodas') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="pid" type="text"
                                        class="form-control @error('pid') is-invalid @enderror" name="pid" required>

                                    @error('pid')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="address"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Adresas') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="address" type="text"
                                        class="form-control @error('address') is-invalid @enderror" name="address" required
                                        autocomplete="street-address">

                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="city"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Miestas') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="city" type="text"
                                        class="form-control @error('city') is-invalid @enderror" name="city" required
                                        autocomplete="address-level2">

                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phoneNum"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Tel. nr.') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="phoneNum" type="tel"
                                        class="form-control @error('phoneNum') is-invalid @enderror" name="phoneNum"
                                        required autocomplete="tel">

                                    @error('phoneNum')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @if (Auth::user()->role == $roleDirector)
                                <div class="row mb-3">
                                    <label for="branch"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Filialas') }}<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <select name="branch" id="branchSelect"
                                            class="form-select @error('branch') is-invalid @enderror" id="branch"
                                            required>
                                            @foreach ($branches as $index => $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->address }}</option>
                                            @endforeach
                                        </select>

                                        @error('branch')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            @if ($employeeForm && Auth::user()->role == $roleDirector)
                            <div class="row mb-3">
                                <label for="image"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Nuotrauka') }}</label>

                                <div class="col-md-6">
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
                                <label for="employmentTime"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Etatas') }}<span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select name="employmentTime" id="employmentTimeSelect"
                                        class="form-select @error('employmentTime') is-invalid @enderror" id="employmentTime"
                                        required>
                                        <option value="1">1</option>
                                        <option value="0.75">0.75</option>
                                        <option value="0.5">0.5</option>
                                    </select>

                                    @error('employmentTime')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="salary"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Alga.') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="salary" type="number" step="0.01" min="0"
                                        class="form-control @error('salary') is-invalid @enderror"
                                        required name="salary">

                                    @error('salary')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                                <div class="row mb-3">
                                    <label for="role"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Rolė') }}<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <select name="role" class="form-select @error('role') is-invalid @enderror"
                                            id="role" required>
                                            @foreach ($roles as $value => $displayName)
                                                <option value="{{ $value }}">@lang('messages.' . $displayName)</option>
                                            @endforeach
                                        </select>

                                        @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @else
                                <div class="row mb-3">
                                    <label for="course"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Mokymo kursas') }}<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <select name="course" class="form-select @error('course') is-invalid @enderror"
                                            id="courseSelect" required>
                                            @foreach ($catCourses as $course)
                                                <option class="cat br{{ $course->fk_BRANCHid }}"
                                                    value="{{ $course->id }}">{{ $course->name }} Kategorija</option>
                                            @endforeach
                                            @foreach ($comCourses as $course)
                                                <option class="com br{{ $course->fk_BRANCHid }}"
                                                    value="{{ $course->id }}">{{ $course->name }}</option>
                                            @endforeach
                                        </select>

                                        @error('course')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="group"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Kursų grupė') }}</label>
                                    <div class="col-md-6">
                                        <select name="group" class="form-select @error('group') is-invalid @enderror"
                                            id="group">
                                            <option value="" selected>Parinkti grupę</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->date_start }} -
                                                    {{ $group->name }}</option>
                                            @endforeach
                                        </select>

                                        @error('group')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <input id="role" value="client" type="hidden" class="form-control"
                                    name="role">

                                <div class="row mb-3">
                                    <label for="prepaid"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Pirminis įnašas.') }}<span class="text-danger">*</span></label>

                                    <div class="col-md-6">
                                        <input id="prepaid" type="number" step="0.01" min="0"
                                            value="0" class="form-control @error('prepaid') is-invalid @enderror"
                                            required name="prepaid">

                                        @error('prepaid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="noTheory"
                                        class="col-md-4 form-check-label text-md-end">{{ __('Kursai eksternu') }}</label>

                                    <div class="col-md-6 ">
                                        <div class="form-check form-switch">
                                            <input id="noTheory" type="checkbox" value="true"
                                                class="form-check-input" role="switch"
                                                @error('noTheory') class="is-invalid @enderror" name="noTheory">
                                        </div>

                                        @error('noTheory')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="extension"
                                        class="col-md-4 form-check-label text-md-end">{{ __('Tobulinimosi sutartis') }}</label>

                                    <div class="col-md-6 ">
                                        <div class="form-check form-switch">
                                            <input id="extension" type="checkbox" value="true"
                                                class="form-check-input" role="switch"
                                                @error('extension') class="is-invalid @enderror" name="extension">
                                        </div>

                                        @error('extension')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Registruoti') }}
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
        document.addEventListener('DOMContentLoaded', function() {
            var branchSelect = document.getElementById('branchSelect');
            var courseSelect = document.getElementById('courseSelect');
            var courseOptions = courseSelect.getElementsByTagName('option');
            var groupSelect = document.getElementById('group');
            var groupOptions = groupSelect.getElementsByTagName('option');

            function filterCourseOptions() {
                courseSelect.value = '';
                var selectedBranchId = branchSelect.value;
                for (var i = 0; i < courseOptions.length; i++) {
                    var option = courseOptions[i];
                    if (option.classList.contains('br' + selectedBranchId)) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                }
            }

            function filterGroupOptions() {
                groupSelect.value = '';

                var selectedText = "";
                Array.from(courseSelect.options).forEach(function(option) {
                    if (option.selected) {
                        selectedText = option.text;
                    }
                });

                if (selectedText != "") {
                    var selectedCoursePrefix = selectedText.endsWith(" Kategorija") ? selectedText.slice(0, -11) :
                        selectedText;

                    Array.from(groupOptions).forEach(function(option) {
                        var optionText = option.text;

                        if (optionText.endsWith(selectedCoursePrefix) || optionText == "Parinkti grupę") {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                } else {
                    Array.from(groupOptions).forEach(function(option) {
                        if (option.text != "Parinkti grupę") {
                            option.style.display = 'none';
                        }
                    });
                }
            }

            function blockSwitch() {
                var option = courseSelect[courseSelect.selectedIndex];
                var isCatOption = option.classList.contains('cat');
                var noTheoryCheckbox = document.getElementById("noTheory");

                if (isCatOption) {
                    noTheoryCheckbox.removeAttribute("disabled");
                } else {
                    noTheoryCheckbox.setAttribute("disabled", "disabled");
                    noTheoryCheckbox.checked = false;
                }
            }

            if (branchSelect != null) {
                filterCourseOptions();
                branchSelect.addEventListener('change', filterCourseOptions);
                branchSelect.addEventListener('change', filterGroupOptions);
            }

            filterGroupOptions()
            courseSelect.addEventListener('change', filterGroupOptions);
            courseSelect.addEventListener('change', blockSwitch);
        });
    </script>

@endsection
