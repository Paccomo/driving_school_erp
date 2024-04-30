<?php

namespace App\Constants;

enum DrivingStatus: string {
    case Reservation = "reservation";
    case Cancel = "canceled";
    case Evaluated = "evaluated";
    case Miss = "client_no_show";
}