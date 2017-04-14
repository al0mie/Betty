<?php

namespace App\Controllers;

use App\Services\BettingService;

/**
 * Class BettingController
 * @package App\Controllers
 */
class BettingController extends Controller
{
    /**
     * @Inject
     * @var BettingService
     */
    private $bettingService;

    /**
     * BettingController constructor.
     * @param BettingService $bettingService
     */
    public function __construct(BettingService $bettingService)
    {
        $this->bettingService = $bettingService;
    }

    /**
     * Start page
     *
     * @return mixed
     */
    public function index()
    {
        return $this->view('bettingsForm');
    }

    /**
     * Start page
     *
     * @return mixed
     */
    public function showScoreForm()
    {
        return $this->view('scoreForm');
    }

    /**
     * Start or accept bets
     */
    public function processGame()
    {
        $this->bettingService->createOrAcceptBet($_POST);
    }

    /**
     * Update score from players
     */
    public function updateScore()
    {
        $this->bettingService->updateScore($_POST);
    }
}