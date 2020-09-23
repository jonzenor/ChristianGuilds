<?php

return [
    'message'  => env('SITE_MESSAGE', null),
    'message_type' => env('SITE_MESSAGE_TYPE', 'info'), // info or warn

    'cache_search_time' => env('CACHE_SEARCH_TIME', 120),

    'input_name_min' => 3,
    'input_name_max' => 255,
    'input_genre_min' => 3,
    'input_genre_max' => 128,
    'input_desc_min' => 3,
    'input_desc_max' => 4000,
    'input_short_genre_min' => 2,
    'input_short_genre_max' => 32,

    'input_pushover_length' => 30,

    'community_guilds_limit' => 5,
    'community_create_invites' => 3,

    'truncate_length' => 32,
];
