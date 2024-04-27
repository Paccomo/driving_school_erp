<?php

namespace App\Constants;

enum RequestStatus: string {
    case Unconfirmed = "unconfirmed";
    case Approved = "approved";
    case Denied = "denied";
}