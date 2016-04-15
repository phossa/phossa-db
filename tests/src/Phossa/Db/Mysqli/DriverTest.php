<?php

namespace Phossa\Db\Mysqli;

use Phossa\Db\Types;

/**
 * Driver test case.
 */
class DriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Driver
     */
    private $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->object = new Driver([
            'db'        => 'mysql',
            'host'      => '127.0.0.1',
            'charset'   => 'utf8'
        ]);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->object = null;
        parent::tearDown();
    }

    /**
     * Call protected/private method of a class.
     *
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod($methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($this->object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->object, $parameters);
    }

    /**
     * getPrivateProperty
     *
     * @param 	string $propertyName
     * @return	the property
     */
    public function getPrivateProperty($propertyName) {
        $reflector = new \ReflectionClass($this->object);
        $property  = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($this->object);
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::extensionLoaded()
     */
    public function testExtensionLoaded()
    {
        $this->assertTrue($this->invokeMethod('extensionLoaded'));
    }

    /**
     * Test get driver name
     *
     * @covers Phossa\Db\Mysqli\Driver::getDriverName()
     */
    public function testGetDriverName()
    {
        $this->assertEquals('mysqli', $this->object->getDriverName());
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::realQuote()
     */
    public function testRealQuote()
    {
        // connect first
        $this->object->connect();

        // quote null
        $this->assertEquals(
            "''",
            $this->invokeMethod('realQuote', [ null, Types::PARAM_NULL] )
        );

        // quote int
        $this->assertEquals(
            "'12'",
            $this->invokeMethod('realQuote', [ 12, Types::PARAM_INT] )
        );

        // quote bool
        $this->assertEquals(
            "'1'",
            $this->invokeMethod('realQuote', [ true, Types::PARAM_BOOL] )
        );
        $this->assertEquals(
            "''",
            $this->invokeMethod('realQuote', [ false, Types::PARAM_BOOL] )
        );

        // quote wild string
        $this->assertEquals(
            "'test\'s'",
            $this->invokeMethod('realQuote', [ "test's", Types::PARAM_STR] )
        );
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::realPing()
     */
    public function testRealPing()
    {
        $this->object->connect();
        $this->assertTrue($this->invokeMethod('realPing'));
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::realSetAttribute()
     * @expectedException Phossa\Db\Exception\LogicException
     * @expectedExceptionMessageRegExp /Unknown attribute/
     */
    public function testRealSetAttribute1()
    {
        $this->invokeMethod(
            'realSetAttribute',
            [ 'test', 'bingo']
        );
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::realGetAttribute()
     * @expectedException Phossa\Db\Exception\LogicException
     * @expectedExceptionMessageRegExp /Unknown attribute/
     */
    public function testRealGetAttribute1()
    {
        $this->invokeMethod(
            'realGetAttribute',
            [ 'test']
        );
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::realSetAttribute()
     */
    public function testRealSetAttribute2()
    {
        $this->object->connect();
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::disConnect()
     * @covers Phossa\Db\Mysqli\Driver::getLink()
     * @covers Phossa\Db\Mysqli\Driver::isConnected()
     */
    public function testDisConnect()
    {
        $this->assertFalse($this->object->isConnected());
        $this->object->connect();
        $this->assertTrue($this->object->getLink() instanceof \mysqli);
        $this->assertTrue($this->object->isConnected());
        $this->object->disconnect();
        $this->assertFalse($this->object->isConnected());
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::ping()
     */
    public function testPing()
    {
        $this->assertFalse($this->object->ping());
        $this->object->connect();
        $this->assertTrue($this->object->ping());
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::setAttribute()
     */
    public function testSetAttribute()
    {

    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::prepare()
     */
    public function testPrepare()
    {
        $stmt = $this->object->prepare('SELECT ? AS col');
        $this->assertTrue($stmt instanceof Statement);
        $res1 = $stmt->execute([2]);
        $this->assertEquals(["2"], $res1->fetchCol('col'));

        $res2  = $stmt->execute([3]);
        $this->assertEquals(["3"], $res2->fetchCol('col'));
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::execute()
     * @covers Phossa\Db\Mysqli\Driver::getLastInsertId()
     */
    public function testExecute()
    {
        // successful execute
        $this->assertTrue(0 === $this->object->execute('
            DROP TABLE IF EXISTS `bingo`
        '));

        $sql = <<<EOF
            CREATE TABLE `bingo` (
                `grp_id`   INT         NOT NULL AUTO_INCREMENT,
                `grp_name` VARCHAR(20) NOT NULL DEFAULT '',
                PRIMARY KEY (`grp_id`)
            )
EOF;
        $this->assertTrue(0 === $this->object->execute($sql));

        $this->assertTrue(1 === $this->object->execute('
            INSERT INTO `bingo` (`grp_name`) VALUES (?)
        ', ['wow']));

        $this->assertEquals(1, $this->object->getLastInsertId());

        $this->assertEquals(
            [["grp_id" => "1", "grp_name" => "wow"]],
            $this->object->query("SELECT * FROM bingo")->fetchRow()
        );

        // failed execute
        $this->assertTrue(false === $this->object->execute($sql));
        $this->assertRegExp("/already exists/i", $this->object->getError());
    }

    /**
     * @covers Phossa\Db\Mysqli\Driver::query()
     */
    public function testQuery()
    {
        $this->object->enableProfiling();

        $res = $this->object->query('SELECT ? AS col', [1]);
        $this->assertEquals(["1"], $res->fetchCol('col'));

        $this->assertEquals(
            "SELECT '1' AS col",
            $this->object->getProfiler()->getSql()
        );

        $res = $this->object->query(
            'SELECT * FROM test WHERE area = :area AND year = :year',
            ['area' => 'China', 'year' => 2010]
        );

        $this->assertEquals(
            "SELECT * FROM test WHERE area = 'China' AND year = '2010'",
            $this->object->getProfiler()->getSql()
        );
    }
}

