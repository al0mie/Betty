<?php

$routes['index'] = [
    'index',
    'controller' => 'Betting',
    'method' => 'index',
];

$routes['showScore'] = [
    'showScore',
    'controller' => 'Betting',
    'method' => 'showScoreForm',
];

$routes['processGame'] = [
    'processGame',
    'controller' => 'Betting',
    'method' => 'processGame',
];

$routes['updateScore'] = [
    'updateScore',
    'controller' => 'Betting',
    'method' => 'updateScore',
];