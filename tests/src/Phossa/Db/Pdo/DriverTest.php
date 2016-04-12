<?php

namespace Phossa\Db\Pdo;

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
            'dsn' => 'mysql:dbname=test;host=127.0.0.1'
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
     * @covers Phossa\Db\Pod\Driver::extensionLoaded()
     */
    public function testExtensionLoaded()
    {
        $this->assertTrue($this->invokeMethod('extensionLoaded'));
    }

    /**
     * Test get driver name
     *
     * @covers Phossa\Db\Pod\Driver::getDriverName()
     */
    public function testGetDriverName()
    {
        // before connect
        $this->assertEquals('pdo', $this->object->getDriverName());

        // connect
        $this->object->connect();
        $this->assertEquals('mysql', $this->object->getDriverName());
    }

    /**
     * @covers Phossa\Db\Pod\Driver::realQuote()
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
     * @covers Phossa\Db\Pod\Driver::setConnectLink()
     */
    public function testSetConnectLink()
    {
        $pdo = new \PDO('mysql:dbname=test;host=127.0.0.1', 'root');
        $this->assertTrue($this->invokeMethod('setConnectLink', [ $pdo ]));
    }

    /**
     * @covers Phossa\Db\Pod\Driver::realPing()
     */
    public function testRealPing()
    {
        $this->object->connect();
        $this->assertTrue($this->invokeMethod('realPing'));
    }
}

