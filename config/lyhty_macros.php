<?php

return [

    'disabled' => [

        \Lyhty\Macros\Str\WrapMacro::class => explode('.', app()->version(), 2)[0] > 9,
        \Lyhty\Macros\Stringable\WrapMacro::class => explode('.', app()->version(), 2)[0] > 9,

    ],

];
