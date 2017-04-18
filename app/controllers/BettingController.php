<?php

namespace App\Controllers;

use App\Services\BettingService;
use App\Validators\BettingValidator;

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
        if (BettingValidator::validateBet($data)) {
            $this->bettingService->createOrAcceptBet($data);
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
        if (BettingValidator::validateScore($data)) {
            if ($this->bettingService->updateScore($data)) {
                return $this->view('success');
            } else {
                return $this->view('error');
            }
        } else {
            return $this->view('error');
        }
    }

 
}
