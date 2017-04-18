<?php

namespace App\Validators;

class BettingValidator
{
    /**
     * Validate data from score form update
     *
     * @param $data
     * @return bool
     */
    public static function validateScore(array $data) : bool
    {
        return isset($data['player_id']) && isset($data['score']) && is_numeric($data['score']) && isset($data['bet_id']) && is_numeric($data['bet_id']);
    }

    /**
     * Validate data from bet form
     *
     * @param array $data
     * @return bool
     */
    public static function validateBet(array $data) : bool
    {
        return isset($data['player_id']) && isset($data['amount']) && is_numeric($data['amount']) && isset($data['game_id']) && is_numeric($data['game_id']);
    }
}