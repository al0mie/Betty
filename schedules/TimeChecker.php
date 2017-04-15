<?php

require_once  __DIR__ . '/../bootstrap/autoload.php';

$bs = $container->get('App\Services\BettingService');

$bs->finishGames();

