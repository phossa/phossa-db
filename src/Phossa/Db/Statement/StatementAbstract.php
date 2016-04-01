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
use Phossa\Db\Driver\DriverInterface;
use Phossa\Db\Result\ResultInterface;
use Phossa\Db\Exception\LogicException;
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
    /**
     * Is this statement prepared
     *
     * @var    bool
     * @access protected
     */
    protected $prepared = false;

    /**
     * The driver
     *
     * @var    DriverInterface
     * @access protected
     */
    protected $driver;

    /**
     * Result prototype
     *
     * @var    ResultInterface
     * @access protected
     */
    protected $result;

    /**
     * {@inheritDoc}
     */
    public function init(
        DriverInterface $driver,
        ResultInterface $resultPrototype
    ) {
        $this->driver = $driver;
        $this->result = $resultPrototype;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isPrepared()/*# : bool */
    {
        return $this->prepared;
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(/*# string */ $sql)
    {
        $this->prepared = false;

        if ($this->driver) {
            // get link first
            $link = $this->driver->getLink();

            // driver specific prepare
            $this->prepared = $this->realPrepare($link, $sql);

            return $this;
        } else {
            throw new LogicException(
                Message::get(Message::DB_SQL_NO_DRIVER),
                Message::DB_SQL_NO_DRIVER
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function execute(array $parameters = [])/*# : ResultInterface */
    {
        $result = clone $this->result;
        if ($this->isPrepared()) {
            $this->realExecute($parameters, $result);
        }
        return $result;
    }


    /**
     * Driver specific prepare statement
     *
     * @param  resource $link
     * @param  string $sql
     * @return bool
     * @access protected
     */
    abstract protected function realPrepare(
        $link,
        /*# string */ $sql
    )/*# : bool */;

    /**
     * Driver specific statement execution
     *
     * @param  array $parameters
     * @param  ResultInterface $result
     * @return void
     * @access protected
     */
    abstract protected function realExecute(
        array $parameters,
        ResultInterface $result
    )/*# : ResultInterface */;
}
