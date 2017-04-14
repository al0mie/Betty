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
}
