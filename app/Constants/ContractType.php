<?php

namespace App\Constants;

enum ContractType: string {
    case TeachingContract = "teaching_contract";
    case ImprovementContract = "improvement_contract";
    case Termination = "teaching_contract_termination";
    case Extension = "teaching_contract_extension";
}