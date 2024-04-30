<?php

namespace App\Http\Controllers\Lessons;

use App\Constants\DrivingStatus;
use App\Constants\Role;
use App\Constants\WeekDay;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DrivingLesson;
use App\Models\Employee;
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

        $currentDateTime = Carbon::now();
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

        $reservations = [];
        $currentDateTime = Carbon::now();
        $instructorLessons = DrivingLesson::where('fk_EMPLOYEEid', $self->fk_instructor)
        ->where('date', '>', $currentDateTime)->get();

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

                if ((strtotime($timeStart) >= strtotime($pt[0]) && strtotime($timeStart) <= strtotime($pt[1]))
                || (strtotime($timeEnd) >= strtotime($pt[0]) && strtotime($timeEnd) <= strtotime($pt[1]))) {
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

        $request->validate([
            'start' => ['required', 'regex:/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'],
        ]);

        $resDate = new DateTime($request->start);
        $resEnd = clone $resDate;
        $resEnd->modify('+2 hours');
        $earlierReservations = DrivingLesson::where('fk_EMPLOYEEid', $self->fk_instructor)
        ->whereBetween('date', [$resDate->format('Y-m-d H:i:s'), $resEnd->format('Y-m-d H:i:s')])->get();

        if ($earlierReservations->isEmpty()) {
            $dl = new DrivingLesson();
            $dl->date = $request->start;
            $dl->status = DrivingStatus::Reservation->value;
            $dl->fk_CLIENTid = $self->id;
            $dl->fk_EMPLOYEEid = $self->fk_instructor;
            $dl->save();

            return redirect()->route('lesson')->with('success', 'Vairavimo pamokos rezervacija sėkminga');
        } else {
            return redirect()->route('lesson')->with('fail', 'Nurodytu laiku instruktorius jau turi kitą rezervaciją');
        }
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
