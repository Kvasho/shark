<?php

return [
    // ადმინისტრატორის ანგარიში, რომელსაც AdminSeeder ქმნის/აახლებს.
    'username' => env('ADMIN_USERNAME', 'admin'),
    'name' => env('ADMIN_NAME', 'Administrator'),
    'email' => env('ADMIN_EMAIL'),

    // საიტის ენები, რომლებზეც ადმინში ხდება ინფორმაციის შეტანა.
    'locales' => [
        'ka' => 'ქართული',
        'en' => 'English',
        'ru' => 'Русский',
    ],
];
