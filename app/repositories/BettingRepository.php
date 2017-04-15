<?php

namespace App\Repositories;

/**
 * Class BettingRepository
 * @package App\Repositories
 */
class BettingRepository extends Repository
{
    /**
     * Statuses for bets
     */
    const STATUS_OFFERED = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_MAKING_SCORE = 3;
    const STATUS_FINISHED = 4;

    /**
     * @param $betId
     * @return mixed
     */
    public function findBetById($betId)
    {
        return $this->findOne('SELECT * FROM bettings WHERE id = ?', [$betId]);
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
        return $this->update('UPDATE bettings SET status = ?, opponent_guid = ?, updated_at = ? WHERE id = ? AND status <> ?', [self::STATUS_ACCEPTED, $data['player_id'], time(), $betId, self::STATUS_FINISHED]);
    }

    /**
     * Update score for user
     *
     * @param $data
     * @param $scoreColumn
     * @return int
     */
    public function updateScore($data, $scoreColumn)
    {
        return $this->update("UPDATE bettings SET {$scoreColumn}  = ?, status = ? WHERE id = ? AND (origin_guid = ? OR opponent_guid = ?) AND status <> ?", [$data['score'], self::STATUS_MAKING_SCORE, $data['bet_id'], $data['player_id'], $data['player_id'], self::STATUS_FINISHED]);
    }

    /**
     * Create a new bet
     *
     * @param $data
     * @param $time
     * @return string
     */
    public function createBet($data, $time)
    {
        return $this->insert('INSERT INTO bettings (origin_guid, game_id, status, start, end, amount, created_at, updated_at)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [$data['player_id'], $data['game_id'], self::STATUS_OFFERED, $time, $time + 10 * 60, $data['amount'], time(), time()]);
    }

    /**
     * Find offered game
     *
     * @param $data
     * @return mixed
     */
    public function findBetToAccept($data)
    {
        return $this->findOne('SELECT * FROM bettings WHERE game_id = ? AND amount = ? AND status = 1 AND origin_guid <> ? AND status <> ?', [$data['game_id'], $data['amount'], $data['player_id'], self::STATUS_FINISHED]);
    }

    /**
     * Find bet for required game
     *
     * @param $gameId
     * @return mixed
     */
    public function findBetFromGame($gameId)
    {
        return $this->findOne('SELECT * FROM bettings WHERE game_id = ?', [$gameId]);
    }

    /**
     * Finish games
     *
     * @return int
     */
    public function finishGames()
    {
        return $this->update('UPDATE bettings set winner = (case 
                                                  when origin_score > opponent_score then 1 
                                                  when opponent_score > origin_score then -1 
                                                  else 0 end),
                                                  status = ?
                                  WHERE end < ?', [self::STATUS_FINISHED, time()]);
    }
}