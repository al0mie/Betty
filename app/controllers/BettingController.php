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
        $data = $_POST;
        if ($this->validateBet($data)) {
            $this->bettingService->createOrAcceptBet($_POST);
            return $this->view('success');
        } else {
            return $this->view('error');
        }
    }

    /**
     * Update score from players
     */
    public function updateScore()
    {
        $data = $_POST;
        if ($this->validateScore($data)) {
            if ($this->bettingService->updateScore($_POST)) {
                return $this->view('success');
            } else {
                return $this->view('error');
            }
        } else {
            return $this->view('error');
        }
    }

    /**
     * Validata data from score form update
     *
     * @param $data
     * @return bool
     */
    protected function validateScore(array $data) : bool
    {
        return isset($data['player_id']) && isset($data['score']) && is_numeric($data['score']) && isset($data['bet_id']) && is_numeric($data['bet_id']);
    }

    /**
     * Validate data from bet form
     *
     * @param array $data
     * @return bool
     */
    protected function validateBet(array $data) : bool
    {
        return isset($data['player_id']) && isset($data['amount']) && is_numeric($data['amount']) && isset($data['game_id']) && is_numeric($data['game_id']);
    }
}
