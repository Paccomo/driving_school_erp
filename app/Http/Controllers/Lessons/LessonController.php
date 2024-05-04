<?php

namespace App\Http\Controllers\Lessons;

use App\Constants\DrivingStatus;
use App\Constants\Role;
use App\Constants\WeekDay;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DrivingLesson;
use App\Models\Employee;
use App\Models\instructorReservedTime;
use App\Models\Person;
use App\Models\TimetableTime;
use Auth;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:instructor,client');
    }

    public function clientLessons() {
        $this->validateClient();

        $self = Client::find(Auth::user()->id);

        if ($self->fk_instructor != null) {
            $instructors = Employee::with(['person'])->find($self->fk_instructor);
            $instructors = collect([$instructors]);
        } else {
            $instructors = Employee::with(['person'])
            ->whereHas('account', function ($query) {
                $query->where('role', Role::Instructor->value);
            })
            ->where('fk_BRANCHid', $self->fk_BRANCHid)
            ->get();
        }

        $currentDateTime = Carbon::now('Europe/Vilnius');
        $lessons = DrivingLesson::with('employee')
        ->where('fk_CLIENTid', $self->id)
        ->where('status', DrivingStatus::Reservation->value)
        ->where('date', '>', $currentDateTime)
        ->orderBy('date', 'asc')
        ->get();
        foreach ($lessons as $l) {
            $datetime = Carbon::parse($l->date);
            $l->time = $datetime->format('H:i');
            $l->date = $datetime->toDateString();
            $l->cancelDate = $datetime->subDay()->toDateString();
        }

        return view('lesson.index', [
            'allInstructors' => $instructors,
            'self' => $self,
            'lessons' => $lessons
        ]);
    }

    public function reservation() {
        $this->validateClient();
        $self = Client::find(Auth::user()->id);
        if ($self->fk_instructor == null)
            return redirect()->route('lesson')->with('fail', 'Jums nėra priskirto instruktoriaus');
        if ((int)$self->lessons <= 0)
            return redirect()->route('lesson')->with('fail', 'Pirma nusipirkite papildomų vairavimo pamokų');

        $possibleTimes = [];
        $weekDays = array_column(WeekDay::cases(), 'value');
        foreach ($weekDays as $day) {
            $dayValues = TimetableTime::where([
                ['week_day', $day],
                ['fk_EMPLOYEEid', $self->fk_instructor]
            ])->whereIn('time_type', ['open', 'break', 'close'])
            ->get()
            ->groupBy('time_type')
            ->map
            ->first();

            if ($dayValues->isNotEmpty()) {
                $opening = $dayValues->where('time_type', 'open')->first()->time;
                $closing = $dayValues->where('time_type', 'close')->first()->time;
                if ($breakStart = $dayValues->where('time_type', 'break')->first() != null)
                    $breakStart = $dayValues->where('time_type', 'break')->first()->time;
                else 
                     $breakStart = null;
                $timeIntervals = $this->splitTimeIntervals($opening, $breakStart, $closing);
                foreach ($timeIntervals as $key => $interval) {
                    $start = Carbon::parse($interval[0]);
                    $end = Carbon::parse($interval[1]);
                    if ($start->diffInHours($end) !== 2) {
                        unset($timeIntervals[$key]);
                    }
                }
                $possibleTimes[$day] = $timeIntervals;
            }
        }

        $irt = instructorReservedTime::where('fk_instructor', $self->fk_instructor)->get();
        foreach($irt as $i) {
            foreach($possibleTimes[$i->day] as $index => $pt) {
                if ((strtotime($i->from) >= strtotime($pt[0]) && strtotime($i->from) < strtotime($pt[1]))
                || (strtotime($i->to) >= strtotime($pt[0]) && strtotime($i->to) < strtotime($pt[1]))) {
                    unset($possibleTimes[$i->day][$index]);
                }
            }
        }

        $reservations = [];
        $currentDateTime = Carbon::now('Europe/Vilnius');
        $instructorLessons = DrivingLesson::where('fk_EMPLOYEEid', $self->fk_instructor)
        ->where('date', '>', $currentDateTime)
        ->where('status', '!=', DrivingStatus::Cancel->value)
        ->get();

        foreach($instructorLessons as $i) {
            $dateTime = new DateTime($i->date);
            $weekday = $dateTime->format('l');
            $weekday = strtolower($weekday);
            foreach ($possibleTimes[$weekday] as $pt) {
                $timeStart = $dateTime->format('H:i');
                $dateTime->add(new DateInterval('PT2H'));
                $timeEnd = $dateTime->format('H:i');
                $dateTime->sub(new DateInterval('PT2H'));

                $start = DateTime::createFromFormat('H:i', $pt[0]);
                $end = DateTime::createFromFormat('H:i', $pt[1]);

                if ((strtotime($timeStart) >= strtotime($pt[0]) && strtotime($timeStart) < strtotime($pt[1]))
                || (strtotime($timeEnd) >= strtotime($pt[0]) && strtotime($timeEnd) < strtotime($pt[1]))) {
                    $reservations[] =  $dateTime->format('Y-m-d\TH:i:s');
                }
            }
        }

        $reservations = array_unique($reservations);
        $reservations = array_values($reservations);
        $eventsStruct = $this->getReservationsForFullCalendar($reservations, $possibleTimes);
        return view('lesson.reservation', ['events' => $eventsStruct]);
    }

    public function reservationSave(Request $request) {
        $this->validateClient();
        $self = Client::find(Auth::user()->id);
        if ($self->fk_instructor == null)
            return redirect()->route('lesson')->with('fail', 'Jums nėra priskirto instruktoriaus');
        if ((int)$self->lessons <= 0)
            return redirect()->route('lesson')->with('fail', 'Pirma nusipirkite papildomų vairavimo pamokų');

        $request->validate([
            'start' => ['required', 'regex:/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'],
        ]);

        $resDate = new DateTime($request->start);
        $resEnd = clone $resDate;
        $resEnd->modify('+1 hour 50 minutes');
        $earlierReservations = DrivingLesson::where('fk_EMPLOYEEid', $self->fk_instructor)
        ->where('status', '!=', DrivingStatus::Cancel->value)
        ->whereBetween('date', [$resDate->format('Y-m-d H:i:s'), $resEnd->format('Y-m-d H:i:s')])->get();

        if ($earlierReservations->isEmpty()) {
            $dl = new DrivingLesson();
            $dl->date = $request->start;
            $dl->status = DrivingStatus::Reservation->value;
            $dl->fk_CLIENTid = $self->id;
            $dl->fk_EMPLOYEEid = $self->fk_instructor;
            $dl->save();

            $self->lessons = (int)$self->lessons - 1;
            $self->save();

            return redirect()->route('lesson')->with('success', 'Vairavimo pamokos rezervacija sėkminga');
        } else {
            return redirect()->route('lesson')->with('fail', 'Nurodytu laiku instruktorius jau turi kitą rezervaciją');
        }
    }

    public function cancel(Request $request) {
        $lesson = DrivingLesson::find($request->id);
        if ($lesson == null)
            abort(Response::HTTP_NOT_FOUND,"Vairavimo pamoka nerasta");

        if ((Auth::user()->role == Role::Client->value && $lesson->fk_CLIENTid != Auth::user()->id) || 
            (Auth::user()->role == Role::Instructor->value && $lesson->fk_EMPLOYEEid != Auth::user()->id))
        {
            abort(Response::HTTP_FORBIDDEN,"Access denied");
        }

        $datetimeCancel = Carbon::parse($lesson->date);
        $datetimeCancel = $datetimeCancel->subDay()->toDateString();
        if ($lesson->status != DrivingStatus::Reservation->value || strtotime(date('Y-m-d')) >= strtotime($datetimeCancel))
            return redirect()->back()->with('fail', "Nepavyko atšaukti vairavimo pamokos");

        $lesson->status = DrivingStatus::Cancel->value;
        $lesson->save();
        return redirect()->back()->with('success', "Rezervacija atšaukta sėkmingai");
    }

    public function instLessons() {
        if (Auth::user()->role != Role::Instructor->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $currentDateTime = Carbon::now('Europe/Vilnius');
        $lessons = DrivingLesson::where([
            ['fk_EMPLOYEEid', Auth::user()->id],
            ['status', DrivingStatus::Reservation->value],
            ['date', '>', $currentDateTime]
        ])->get();
        $clientIds = $lessons->pluck('fk_CLIENTid')->unique()->toArray();
        $clients = Person::whereIn('id', $clientIds)->get()->keyBy('id')->toArray();
        $lessonsAsEvents = $this->convertLessonsAsFullCalendarEvents($lessons, $clients);
        return view('lesson.timetable', ['events' => $lessonsAsEvents]);
    }

    public function gradeForm() {
        if (Auth::user()->role != Role::Instructor->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $currentDateTime = Carbon::now('Europe/Vilnius');
        $lessons = DrivingLesson::with('client')
            ->where([
            ['fk_EMPLOYEEid', Auth::user()->id],
            ['status', DrivingStatus::Reservation->value],
            ['date', '<=', $currentDateTime]
        ])->get();

        return view('lesson.grade', ['lessons' => $lessons]);
    }

    public function grade(Request $request) {
        if (Auth::user()->role != Role::Instructor->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');

        $noShows = [];
        $grades = [];

        foreach ($request->except('_token') as $inputName => $value) {
            if (strpos($inputName, 'noShow') === 0) {
                $lessonId = substr($inputName, strlen('noShow'));
                if ($value == "true")
                    $noShows[$lessonId] = $lessonId;
            } elseif (strpos($inputName, 'grade') === 0) {
                $lessonId = substr($inputName, strlen('grade'));
                $grades[] = ['lesson' => $lessonId, 'grade' => $value];
            }
        }

        foreach ($noShows as $ns) {
            $lesson = DrivingLesson::find($ns);
            if ($lesson != null && $lesson->fk_EMPLOYEEid == Auth::user()->id) {
                $lesson->status = DrivingStatus::Miss->value;
                $lesson->save();
            }
        }

        foreach ($grades as $g) {
            $lesson = DrivingLesson::find($g['lesson']);
            if ($lesson != null && $lesson->fk_EMPLOYEEid == Auth::user()->id && ctype_digit($g['grade']) && $g['grade'] >= 1 && $g['grade'] <= 10) {
                $lesson->status = DrivingStatus::Evaluated->value;
                $lesson->grade = $g['grade'];
                $lesson->save();
            }
        }

        return redirect()->route('home')->with('success', "Įvertinimai surašyti");
    }

    private function convertLessonsAsFullCalendarEvents($lessons, $clients) {
        $eventStruct = [];
        foreach ($lessons as $key => $l)  {
            $lesson = $l->date;
            $lesson = Carbon::parse($lesson);
            $lessonStart = $lesson->format('Y-m-d\TH:i:s');
            $lesson->addHours(1)->addMinutes(30);
            $lessonEnd = $lesson->format('Y-m-d\TH:i:s');
            $client = $clients[$l->fk_CLIENTid]['name'] . " " . $clients[$l->fk_CLIENTid]['surname'];

            $eventStruct[] = [
                'title' => $client,
                'start' => $lessonStart,
                'end' => $lessonEnd,
                'backgroundColor' => '#757575',
                'borderColor' => 'transparent'
            ];
        }

        return $eventStruct;
    }

    private function getReservationsForFullCalendar($reservedTimes, $timeOptions) {
        $eventStruct = [];
        foreach ($timeOptions as $key => $to)  {
            foreach ($to as $singleOption) {
                $formattedTime = new DateTime($singleOption[0]);
                $formattedTime->modify('+1 day');
                $formattedTime = $formattedTime->format('Y-m-d\TH:i:s');

                $eventStruct[] = [
                    'title' => 'Rezervuoti',
                    'rrule' => [
                        'freq' => 'weekly',
                        'byweekday' => [substr($key, 0, 2)],
                        'dtstart' => $formattedTime,
                    ],
                    'duration' => '01:30',
                    'exdate' => $reservedTimes
                ];
            }
        }

        return $eventStruct;
    }

    private function validateClient() {
        if (Auth::user()->role != Role::Client->value)
            abort(Response::HTTP_FORBIDDEN, 'Access denied.');
    }

    private function generateIntervals($startTime, $endTime, $step) {
        $intervals = [];
        $currentTime = clone $startTime;
        while ($currentTime < $endTime) {
            $nextEndTime = clone $currentTime;
            $nextEndTime->modify('+' . $step . ' hours');
            if ($nextEndTime <= $endTime) {
                $intervals[] = [$currentTime->format('H:i'), $nextEndTime->format('H:i')];
            } else {
                $intervals[] = [$currentTime->format('H:i'), $endTime->format('H:i')];
                break;
            }
            $currentTime->modify('+' . $step . ' hours');
        }
        return $intervals;
    }
    
    private function splitTimeIntervals($opening, $breakStart, $closing) {
        $intervals = [];
        $openingTime = new DateTime($opening);
        $breakStartTime = $breakStart != null ? new DateTime($breakStart) : null;
        $closingTime = new DateTime($closing);
    
        if ($breakStartTime != null) {
            $intervals = array_merge($intervals, $this->generateIntervals($openingTime, $breakStartTime, 2));
            $intervals = array_merge($intervals, $this->generateIntervals($breakStartTime->modify('+1 hour'), $closingTime, 2));
        } else {
            $intervals = $this->generateIntervals($openingTime, $closingTime, 2);
        }
    
        return $intervals;
    }
}
