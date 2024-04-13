<?php

return [
    'required' => 'Šis laukas yra būtinas.',
    'string' => 'Informacija privalo būti tekstinio formato.',
    'email' => 'Nekorektiškas el. pašto adresas.',
    'max' => [
        'array' => 'Negali būti daugiau nei :max reikšmių.',
        'file' => 'Maksimalus failo dydis - :max kilobaitai.',
        'numeric' => 'Reikšmė turi būti ne didesnė nei :max.',
        'string' => 'Pateikiamą reikšmę negali sudaryti daugiau :max simbolių.',
    ],
    'enum' => 'Pateikta reikšmė yra netinkama',
    'regex' => 'Pateiktos reikšmės formatas yra nekorektiškas',
    'integer' => 'Pateikiama informacija turi būti natūralusis skaičius',
    'digits' => 'Skaitmenų kiekis turi būti: :digits.',
    'gt' => [
        'array' => 'Įvestas per mažas reikšmių kiekis',
        'file' => 'Failas turi būti didesnis nei :value kilobaitai.',
        'numeric' => 'Reikšmė turi būti didesnė nei :value.',
        'string' => 'Reikšmę turi sudaryti daugiau nei :value simboliai.',
    ],
    'gte' => [
        'array' => 'Įvestas per mažas reikšmių kiekis',
        'file' => 'Failas turi būti ne mažesnis nei :value kilobaitai.',
        'numeric' => 'Reikšmė turi būti ne mažesnė nei :value.',
        'string' => 'Reikšmę turi sudaryti mažiausiai :value simboliai.',
    ],
    'lte' => [
        'array' => 'Įvestas per didelis reikšmių kiekis',
        'file' => 'Failas turi būti ne didesnis nei :value kilobaitai.',
        'numeric' => 'Reikšmė turi būti ne didesnė nei :value.',
        'string' => 'Reikšmę turi sudaryti daugiausiai :value simboliai.',
    ],
    'exists' => 'Pateikta neegzistuojanti reikšmė.',
    'numeric' => 'Reikšmė turi būti skaičius.',
    'image' => 'Pateikiamas failas privalo būti paveikslėlis',
    'array' => 'Laukas :attribute turi būti elemntų grupės formatas.',
    'date_format' => 'Laukas :attribute turi atitikti :format struktūrą.',
    'after' => 'Lauko reikšmė yra per anksti.',
];