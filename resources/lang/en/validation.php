<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravue Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'document' => [
        'cnpj' => [
            'invalid' => 'The :attribute isn\'t a valid CNPJ.',
            'size' => 'The :attribute must have 14 digits to be a valid CNPJ.',
        ],
        'cpf' => [
            'invalid' => 'The :attribute isn\'t a valid CPF.',
            'size' => 'The :attribute must have 11 digits to be a valid CPF.',
        ],
        'generic' => [
            'invalid' => 'The :attribute isn\'t a valid CNPJ or CPF.',
            'size' => 'The :attribute must have 11 or 14 digits to be a valid CNPJ or CPF.',
        ],
    ],
    'phone' => [
        'generic' => 'The :attribute isn\'t a valid phone.',
        'cellphone' => 'The :attribute isn\'t a valid cellphone.',
        'local_fare' => 'The :attribute isn\'t a valid local fare phone.',
        'non_regional' => 'The :attribute isn\'t a valid non regional phone.',
        'phone' => 'The :attribute isn\'t a valid phone.',
        'public_services' => 'The :attribute isn\'t a valid public services phone.',
    ],

];
