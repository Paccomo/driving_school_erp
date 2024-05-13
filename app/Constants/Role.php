<?php

namespace App\Constants;

enum Role: string {
    case Director = "director";
    case Administrator = "administrator";
    case Instructor = "instructor";
    case Accountant = "accountant";
    case Client = "client";
    case Other = "other";
}