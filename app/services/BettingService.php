<?php

namespace App\Services;

use App\Repositories\BettingRepository;

class BettingService
{
    /**
     * @var BettingRepository
     */
    private $db;

    /**
     * Statuses for bets
     */
    const STATUS_OFFERED = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_MAKING_SCORE = 3;
    const STATUS_FINISHED = 4;

    /**
     * BettingService constructor.
     * @param BettingRepository $bettingRepository
     */
    public function __construct(BettingRepository $bettingRepository)
    {
        $this->db = $bettingRepository;
    }

    /**
     * Update score for required bet
     *
     * @param $data
     */
    public function updateScore($data)
    {
        $bet = $this->findBetById($data['bet_id']);
        if ($data['player_id'] == $bet->origin_guid) {
            $scoreColumn = 'origin_score';
        } else if ($data['player_id'] == $bet->opponent_guid) {
            $scoreColumn = 'opponent_score';
        } else return;

        $this->db->update("UPDATE bettings SET {$scoreColumn}  = ?, status = ? WHERE id = ? AND (origin_guid = ? OR opponent_guid = ?) AND status <> ?", [$data['score'], self::STATUS_MAKING_SCORE, $data['bet_id'], $data['player_id'], $data['player_id'], self::STATUS_FINISHED]);
    }

    /**
     * Check if 
     * 
     * @param $data
     */
    public function createOrAcceptBet($data) {
        
        $offeredBet = $this->findBetToAccept($data);

        if ($offeredBet) {
            $this->acceptBet($data, $offeredBet->id);
        } else {
            $this->createBet($data);
        }
    }

    /**
     * Accept game for opponent player
     *
     * @param $data
     * @param $betId
     * @return int
     */
    public function acceptBet($data, $betId)
    {
        return $this->db->update('UPDATE bettings SET status = ?, opponent_guid = ?, updated_at = ? WHERE id = ? AND status <> ?', [self::STATUS_ACCEPTED, $data['player_id'], time(), $betId, self::STATUS_FINISHED]);
    }

    /**
     * Create game, if there are not offered
     *
     * @param $data
     * @return string
     */
    public function createBet($data)
    {
        $bet = $this->findBetFromGame($data['game_id']);

        $gameStart = $bet ? $bet->start : time();

        return $this->db->insert('INSERT INTO bettings (origin_guid, game_id, status, start, end, amount, created_at, updated_at)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [$data['player_id'], $data['game_id'], self::STATUS_OFFERED, $gameStart, $gameStart + 10 * 60, $data['amount'], time(), time()]);
    }

    /**
     * Find offered game
     *
     * @param $data
     * @return mixed
     */
    public function findBetToAccept($data)
    {
        return $this->db->findOne('SELECT * FROM bettings WHERE game_id = ? AND amount = ? AND status = 1 AND origin_guid <> ? AND status <> ?', [$data['game_id'], $data['amount'], $data['player_id'], self::STATUS_FINISHED]);
    }

    /**
     * Find bet for required game
     *
     * @param $gameId
     * @return mixed
     */
    public function findBetFromGame($gameId)
    {
        return $this->db->findOne('SELECT * FROM bettings WHERE game_id = ?', [$gameId]);
    }
}
