<?php

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;

$channels = [];
foreach (['error', 'warning', 'info', 'debug'] as $level) {
    $channels[$level] = [
        'driver' => 'monolog',
        'level' => $level,
        'handler' => RotatingFileHandler::class,
        'handler_with' => [
            'filename' => storage_path('logs/'.$level.'.log'),
            'maxFiles' => 0,
            'bubble' => false,
        ],
        'formatter' => LineFormatter::class,
        'formatter_with' => [
            'allowInlineLineBreaks' => true,
            'ignoreEmptyContextAndExtra' => true,
        ],
    ];
}

return [
    'default' => 'stack',
    'channels' => ['stack' => [
        'driver' => 'stack',
        'channels' => array_keys($channels),
        'ignore_exceptions' => false,
    ]] + $channels,
];
