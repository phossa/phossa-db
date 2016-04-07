<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa\Db
 * @copyright 2015 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa\Db\Statement;

use Phossa\Db\Message\Message;
use Phossa\Db\Driver\ErrorTrait;
use Phossa\Db\Driver\DriverInterface;
use Phossa\Db\Result\ResultInterface;
use Phossa\Db\Exception\RuntimeException;

/**
 * Statement
 *
 * @abstract
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     StatementInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
abstract class StatementAbstract implements StatementInterface
{
    use ErrorTrait;

    /**
     * Is this statement prepared(OK)
     *
     * @var    null|resource
     * @access protected
     */
    protected $prepared;

    /**
     * Result prototype
     *
     * @var    ResultInterface
     * @access protected
     */
    protected $result_prototype;

    /**
     * Destructor
     *
     * @access public
     */
    public function __destruct()
    {
        if ($this->isSuccessful()) {
            $this->realDestruct();
        }
    }

    /**
     * Set driver and result
     *
     * @param  DriverInterface $driver
     * @param  ResultInterface $result_prototype
     * @return this
     * @access public
     */
    public function __invoke(
        DriverInterface $driver,
        ResultInterface $result_prototype
    ) {
        $this->setDriver($driver);
        $this->result_prototype = $result_prototype;
        return $this;
    }

    /**
     * Override isSuccessful in ErrorTrait
     *
     * {@inheritDoc}
     */
    public function isSuccessful()/*# : bool */
    {
        return null === $this->prepared ? false : true;
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(/*# string */ $sql)
    {
        // can not prepare twice
        if (null !== $this->prepared) {
            throw new RuntimeException(
                Message::get(Message::DB_SQL_PREPARED_TWICE),
                Message::DB_SQL_PREPARED_TWICE
            );
        }

        $stmt = $this->realPrepare(
            $this->getDriver()->getLink(),
            (string) $sql
        );

        if ($stmt) {
            $this->prepared = $stmt;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(array $parameters = [])/*# : ResultInterface */
    {
        $result = clone $this->result_prototype;
        $link   = $this->getDriver()->getLink();

        // init result
        $result($link, $this->prepared);

        // execute statement
        if ($this->isSuccessful()) {
            $this->realExecute($parameters);
        }

        return $result;
    }

    /**
     * Driver specific prepare statement
     *
     * @param  resource $link db link resource
     * @param  string $sql
     * @return resource|false
     * @access protected
     */
    abstract protected function realPrepare($link, /*# string */ $sql);

    /**
     * Driver specific statement execution
     *
     * @param  array $parameters
     * @return void
     * @access protected
     */
    abstract protected function realExecute(array $parameters);

    /**
     * Driver specific destruction
     *
     * @access protected
     */
    abstract protected function realDestruct();
}
