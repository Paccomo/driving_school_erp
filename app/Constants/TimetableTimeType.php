<?php

namespace App\Constants;

enum TimetableTimeType: string {
    case Open = "open";
    case Break = "break";
    case Close = "close";
}