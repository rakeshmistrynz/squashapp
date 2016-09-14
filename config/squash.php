<?php

    /*
    |--------------------------------------------------------------------------
    | Squash Booking and User Config
    |--------------------------------------------------------------------------
    |
    | This file is for storing the user types and defining booking rights for the booking system.
    |
    */

return [

    'user_types'=>[
        'junior','senior','coach','administrator'
    ],

    'administrator'=>[
      'administrator'
    ],

    'block_book'=>[
        'coach','administrator'
    ],

    'club+member'=>[
        'coach','administrator'
    ],

    'book_between_5_7pm'=>[
        'senior','coach','administrator'
    ]

];



