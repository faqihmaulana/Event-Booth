<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Midtrans credentials
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', true),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', false),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),



];