<?php

namespace Phossa\Db;

/**
 * Types test case.
 */
class TypesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Event
     */
    protected $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @covers Phossa\Db\Types::guessType()
     */
    public function testGuessType()
    {
        // null
        $this->assertEquals(Types::PARAM_NULL, Types::guessType(null));

        // int
        $this->assertEquals(Types::PARAM_INT, Types::guessType(12));

        // bool
        $this->assertEquals(Types::PARAM_BOOL, Types::guessType(false));

        // string
        $this->assertEquals(Types::PARAM_STR, Types::guessType('test'));
    }
}

