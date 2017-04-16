<?php

namespace App\Services;

use App\Repositories\BettingRepository;
use App\Repositories\Repository;

class BettingService
{
    /**
     * @var Repository
     */
    private $bettingRepository;

    /**
     * BettingService constructor.
     * @param BettingRepository $bettingRepository
     */
    public function __construct(BettingRepository $bettingRepository)
    {
        $this->bettingRepository = $bettingRepository;
    }

    /**
     * Update score for required bet
     *
     * @param $data
     */
    public function updateScore($data)
    {
        $bet = $this->bettingRepository->findBetById($data['bet_id']);
        if (!$bet) {
            return false;
        }
        if ($data['player_id'] == $bet->origin_guid) {
            $scoreColumn = 'origin_score';
        } else if ($data['player_id'] == $bet->opponent_guid) {
            $scoreColumn = 'opponent_score';
        } else return false;

        return $this->bettingRepository->updateScore($data, $scoreColumn);
    }

    /**
     * Check if
     *
     * @param $data
     */
    public function createOrAcceptBet($data)
    {
        $offeredBet = $this->bettingRepository->findBetToAccept($data);
        if ($offeredBet) {
            $this->bettingRepository->acceptBet($data, $offeredBet->id);
        } else {
            $this->createBet($data);
        }
    }

    /**
     * Create game, if there are not offered
     *
     * @param $data
     * @return string
     */
    public function createBet($data)
    {
        $bet = $this->bettingRepository->findBetFromGame($data['game_id']);

        $gameStart = $bet ? $bet->start : time();

        return $this->bettingRepository->createBet($data, $gameStart);
    }

    /**
     * Check finished games
     *
     * @return int
     */
    public function finishGames()
    {
        $this->bettingRepository->finishGames();
    }
}
