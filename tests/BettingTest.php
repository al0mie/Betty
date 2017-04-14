<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

use App\Services\BettingService;
use App\Repositories\BettingRepository;

class MyGuestbookTest extends TestCase
{
    use TestCaseTrait;

    private $bettingService;
    private $db;

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        $this->db = new BettingRepository();
        $this->bettingService = new BettingService($this->db);

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
        $this->assertEquals(1, $this->getConnection()->getRowCount('bettings'), "Pre-Condition");
        $this->bettingService->createBet(['player_id' => 'user 1', 'amount' => 20, 'game_id' => 1]);
        $this->assertEquals(2, $this->getConnection()->getRowCount('bettings'), "After insert");
    }

    public function testCreateNotValidBet()
    {
        $this->bettingService->createBet(['player_id' => 'user 1', 'amount' => 20, 'game_id' => 1]);
        $this->assertEquals(2, $this->getConnection()->getRowCount('bettings'), "After insert");
    }
}
