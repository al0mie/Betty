<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

use App\Services\BettingService;
use App\Repositories\Repository;

class BettingTest extends TestCase
{
    use TestCaseTrait;

    private $bettingService;
    private $db;

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $this->db = $container->get('App\Repositories\BettingRepository');
        $this->bettingService =  $container->get('App\Services\BettingService');

        $pdo = $this->db->getConnection();
        return $this->createDefaultDBConnection($pdo);
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(dirname(__FILE__) . '/seeds/bettings-seed.xml');
    }

    public function testCreateBet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('bettings'), 'Pre-Condition');
        $this->bettingService->createBet(['player_id' => 'user 1', 'amount' => 20, 'game_id' => 1]);
        $this->assertEquals(2, $this->getConnection()->getRowCount('bettings'), "After insert");
    }

    public function testCreateNotValidBet()
    {
        $this->bettingService->createBet(['player_id' => 'user 1', 'amount' => 20, 'game_id' => 1]);
        $this->assertEquals(2, $this->getConnection()->getRowCount('bettings'), 'After insert');
    }

    public function testFindBetToAccept()
    {
        $originUser = ['player_id' => 'user 1', 'amount' => 20, 'game_id' => 1];
        $opponentUser = ['player_id' => 'user 2', 'amount' => 20, 'game_id' => 1];

        $this->bettingService->createBet($originUser);

        $offeredBet = $this->bettingService->findBetToAccept($opponentUser);

        $this->assertEquals($offeredBet->origin_guid, $originUser['player_id'], 'Assert origin_guid');
        $this->assertEquals($offeredBet->opponent_guid, '', 'Assert opponent_guid');
        $this->assertEquals($offeredBet->status, 1, 'Assert opponent_guid');
    }

    public function testAcceptBet()
    {
        $originUser = ['player_id' => 'user 1', 'amount' => 20, 'game_id' => 3];
        $opponentUser = ['player_id' => 'user 2', 'amount' => 20, 'game_id' => 3];

        $betId = $this->bettingService->createBet($originUser);
        $this->bettingService->acceptBet($opponentUser, $betId);

        $acceptedBet = $this->bettingService->findBetById($betId);

        $this->assertEquals($acceptedBet->origin_guid, $originUser['player_id'], 'Assert origin_guid');
        $this->assertEquals($acceptedBet->opponent_guid, $opponentUser['player_id'], 'Assert opponent_guid');
        $this->assertEquals($acceptedBet->status, 2, 'Assert status');
    }

    public function testCreateNewBetForSameGameBet()
    {
        $originUser = ['player_id' => 'user 1', 'amount' => 20, 'game_id' => 3];
        $opponentUser = ['player_id' => 'user 2', 'amount' => 25, 'game_id' => 3];

        $this->bettingService->createOrAcceptBet($originUser);
        $this->bettingService->createOrAcceptBet($opponentUser);

        $this->assertEquals(3, $this->getConnection()->getRowCount('bettings'), 'Assert new bet for a single game');
    }

    public function testAcceptBetForSameGameBet()
    {
        $originUser = ['player_id' => 'user 1', 'amount' => 20, 'game_id' => 3];
        $opponentUser = ['player_id' => 'user 2', 'amount' => 20, 'game_id' => 3];

        $this->bettingService->createOrAcceptBet($originUser);
        $this->bettingService->createOrAcceptBet($opponentUser);

        $this->assertEquals(2, $this->getConnection()->getRowCount('bettings'), 'Assert accept bet for a single game');
    }

    public function testUpdateScore()
    {
        $originUser = ['player_id' => 'user 1', 'amount' => 20, 'game_id' => 3];
        $opponentUser = ['player_id' => 'user 2', 'amount' => 20, 'game_id' => 3];

        $betId = $this->bettingService->createBet($originUser);

        $this->bettingService->acceptBet($opponentUser, $betId);

        $scoreDataOriginPlayer = ['bet_id' => $betId, 'player_id' => $originUser['player_id'], 'score' => 100];
        $scoreDataOpponentPlayer = ['bet_id' => $betId, 'player_id' => $opponentUser['player_id'], 'score' => 200];

        $this->bettingService->updateScore($scoreDataOriginPlayer);

        $acceptedBet = $this->bettingService->findBetById($betId);

        /**
         * Check after origin user update score
         */
        $this->assertEquals($acceptedBet->status, 3, 'Assert status');
        $this->assertEquals($acceptedBet->origin_score, $scoreDataOriginPlayer['score'], 'Assert origin score');
        $this->assertEquals($acceptedBet->opponent_score, 0, 'Assert opponent score');

        /**
         * Check after opponent update score
         */
        $this->bettingService->updateScore($scoreDataOpponentPlayer);
        $acceptedBet = $this->bettingService->findBetById($betId);
        $this->assertEquals($acceptedBet->opponent_score, $scoreDataOpponentPlayer['score'], 'Assert opponent score after update');
        $this->assertEquals($acceptedBet->origin_score, $scoreDataOriginPlayer['score'], 'Assert opponent score');
    }
}
