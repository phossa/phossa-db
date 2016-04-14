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
use Phossa\Db\Driver\DriverAwareTrait;
use Phossa\Db\Exception\RuntimeException;
use Phossa\Db\Driver\DriverAwareInterface;

/**
 * Statement
 *
 * @abstract
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     StatementInterface
 * @see     DriverAwareInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
abstract class StatementAbstract implements StatementInterface, DriverAwareInterface
{
    use DriverAwareTrait;

    /**
     * prepared statement
     *
     * @var    resource
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
     * Constructor
     *
     * @param  DriverInterface $driver
     * @param  ResultInterface $result_prototype
     * @access public
     */
    public function __construct(
        DriverInterface $driver = null,
        ResultInterface $result_prototype = null
    ) {
        if (null !== $driver) {
            $this($driver, $result_prototype);
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
     * {@inheritDoc}
     */
    public function prepare(/*# string */ $sql)/*# : bool */
    {
        // can not prepare twice
        if ($this->prepared) {
            throw new RuntimeException(
                Message::get(Message::DB_SQL_PREPARED_TWICE),
                Message::DB_SQL_PREPARED_TWICE
            );
        }

        // flush driver error
        $this->flushError();

        // prepare statement
        $this->prepared = $this->realPrepare(
            $this->getDriver()->getLink(),
            (string) $sql
        );

        if ($this->prepared) {
            return true;
        }

        // set driver error
        $this->setError($this->getDriver()->getLink());
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(array $parameters = [])
    {
        // flush driver error
        $this->flushError();

        if ($this->prepared) {
            $result = clone $this->result_prototype;
            $result($this->prepared);

            // execute
            if (false === $this->realExecute($parameters)) {
                // set driver error
                $this->setError($this->prepared);
                return false;
            }
            return $result;
        } else {
            $this->setError(Message::get(Message::DB_SQL_NOT_PREPARED));
            return false;
        }
    }

    /**
     * Set driver error
     *
     * @param  resource $resource
     * @access protected
     */
    protected function setError($resource)
    {
        if (is_string($resource)) {
            $this->getDriver()->setError(
                $resource, -1
            );
        } else {
            $this->getDriver()->setError(
                $this->realError($resource),
                $this->realErrorCode($resource)
            );
        }
    }

    /**
     * flush driver error
     *
     * @access protected
     */
    protected function flushError()
    {
        $this->getDriver()->setError();
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
     * @return bool
     * @access protected
     */
    abstract protected function realExecute(array $parameters)/*# : bool */;

    /**
     * Statement specific error
     *
     * @param  resource $resource
     * @return string
     * @access protected
     */
    abstract protected function realError($resource)/*# : string */;

    /**
     * Statement specific error code
     *
     * @param  resource $resource
     * @return string
     * @access protected
     */
    abstract protected function realErrorCode($resource)/*# : string */;
}
