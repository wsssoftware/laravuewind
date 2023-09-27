<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravuewind Language Lines
    |--------------------------------------------------------------------------
    |
    */

    'document' => [
        'cnpj' => [
            'invalid' => 'O campo :attribute não é um CNPJ válido.',
            'size' => 'O campo :attribute deve ter 14 dígitos para ser um CNPJ válido.'
        ],
        'cpf' => [
            'invalid' => 'O campo :attribute não é um CPF válido.',
            'size' => 'O campo :attribute deve ter 11 dígitos para ser um CPF válido.'
        ],
        'generic' => [
            'invalid' => 'O campo :attribute não é um CNPJ ou CPF válido.',
            'size' => 'O campo :attribute deve ter 11 dígitos para CPF ou 14 dígitos para CNPJ.'
        ]
    ],
    'phone' => [
        'generic' => 'O campo :attribute não é um telefone válido.',
        'cellphone' => 'O campo :attribute não é um celular válido.',
        'local_fare' => 'O campo :attribute não é um telefone de tarifa local válido.',
        'non_regional' => 'O campo :attribute não é um telefone não regional válido.',
        'phone' => 'O campo :attribute não é um telefone fixo válido.',
        'public_services' => 'O campo :attribute não é um telefone de serviços públicos válido.',
    ],


];
