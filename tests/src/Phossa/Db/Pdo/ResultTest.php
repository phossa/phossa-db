<?php

namespace Phossa\Db\Pdo;

/**
 * Result test case.
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Driver
     */
    private $driver;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->driver = new Driver([
            'dsn' => 'mysql:dbname=mysql;host=127.0.0.1'
        ]);
    }

    /**
     *
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->driver = null;
        parent::tearDown();
    }

    /**
     * @covers Phossa\Db\Pod\Result:isQuery()
     */
    public function testIsQuery()
    {
        $res = $this->driver->query("SELECT 1");
        $this->assertTrue($res->isQuery());

        $res = $this->driver->query("DROP TABLE IF EXISTS test");
        $this->assertFalse($res->isQuery());
    }

    /**
     * @covers Phossa\Db\Pod\Result:fieldCount()
     */
    public function testFieldCount()
    {
        $res = $this->driver->query("SELECT 1, 2");
        $this->assertTrue($res->fieldCount() === 2);
    }

    /**
     * @covers Phossa\Db\Pod\Result:rowCount()
     */
    public function testRowCount()
    {
        $res = $this->driver->query("SELECT 1, 2");
        $this->assertEquals(1, $res->rowCount());
    }

    /**
     * Tests Result->affectedRows()
     */
    public function testAffectedRows()
    {
        $res = $this->driver->query("SELECT 1, 2");
        $this->assertEquals(1, $res->affectedRows());
    }
}

