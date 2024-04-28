<?php

namespace App\Constants;

enum DocumentType: string {
    case Theory = "theory_exam";
    case Medical = "medical_certificate";
}