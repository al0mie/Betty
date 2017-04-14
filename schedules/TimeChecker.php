<?php

require_once  __DIR__. '/../vendor/autoload.php';

use App\Services\BettingsService;

$bs = new BettingsService();

$bs->finishGames();

