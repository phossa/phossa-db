<?php

namespace Phossa\Db\Pdo;

/**
 * Statement test case.
 */
class StatementTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Driver
     */
    private $driver;

    /**
     *
     * @var Statement
     */
    private $statement;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->driver = new Driver([
            'dsn' => 'mysql:dbname=mysql;host=127.0.0.1;charset=utf8'
        ]);
        $this->statement = new Statement($this->driver, new Result());
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->driver = null;
        $this->statement = null;
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
    public function getPrivateProperty($propertyName, $object = null) {
        $obj = $object ?: $this->object;
        $reflector = new \ReflectionClass($obj);
        $property  = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($obj);
    }

    /**
     * @covers Phossa\Db\Pdo\Statement::__construct()
     */
    public function testConstruct()
    {
        $driver = $this->driver;
        $stmt = $this->statement;
        $this->assertTrue($driver === $this->getPrivateProperty('driver', $stmt));
    }

    /**
     * Test no sql prepared yet
     *
     * @covers Phossa\Db\Pdo\Statement::execute()
     */
    public function testExecute1()
    {
        $res = $this->statement->execute([1]);
        $this->assertRegExp("/No statement/", $this->driver->getError());
    }

    /**
     * @covers Phossa\Db\Pdo\Statement::prepare()
     */
    public function testPrepare1()
    {
        $this->assertTrue($this->statement->prepare('SELECT ?, ?'));
    }

    /**
     * @covers Phossa\Db\Pdo\Statement::prepare()
     * @covers Phossa\Db\Pdo\Statement::execute()
     */
    public function testExecute2()
    {
        // must emulate
        $this->driver->setAttribute('PDO::ATTR_EMULATE_PREPARES', true);

        $this->statement->prepare("SELECT :idx, :color");
        $res = $this->statement->execute(['idx' => 1, 'color' => 'red']);
        $this->assertEquals([[1 => "1", "red" => "red"]],$res->fetchRow());
    }
}

