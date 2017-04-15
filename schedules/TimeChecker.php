<?php

require_once  __DIR__. '/../bootstrap/Bootstrap.php';

use App\Services\BettingService;

$bs = $container->get('BettingService');
$bs->finishGames();

