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
     * @var    mixed
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
     * The execution time
     *
     * @var    float
     * @access protected
     */
    protected $execution_time = 0.0;

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
     * @return $this
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
        try {
            $this->prepared = $this->realPrepare(
                $this->getDriver()->getLink(),
                (string) $sql
            );
        } catch (\Exception $e) {
            $this->prepared = false;
        }

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

        // init execution time
        $this->execution_time = 0.0;

        if ($this->prepared) {
            $result = clone $this->result_prototype;
            $result($this->prepared);

            // close previous statement if any
            $this->closePrevious();

            // start time
            $time = microtime(true);

            // execute
            try {
                if (false === $this->realExecute($parameters)) {
                    // set driver error
                    $this->setError($this->prepared);
                    return false;
                }
            } catch (\Exception $e) {
                $this->setError($this->prepared);
                return false;
            }

            $this->execution_time = microtime(true) - $time;

            return $result;
        } else {
            $this->setError(Message::get(Message::DB_SQL_NOT_PREPARED));
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExecutionTime()/*# : float */
    {
        return (float) $this->execution_time;
    }

    /**
     * Close previous prepared statement
     *
     * @return $this
     * @access protected
     */
    protected function closePrevious()
    {
        static $previous = [];

        $id = spl_object_hash($this->getDriver());
        if (isset($previous[$id]) && $previous[$id] !== $this->prepared) {
            $this->realClose($previous[$id]);
        }
        $previous[$id] = $this->prepared;

        return $this;
    }

    /**
     * Set driver error
     *
     * @param  mixed $resource
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
     * @param  mixed $link db link resource
     * @param  string $sql
     * @return mixed|false
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
     * @param  mixed $resource
     * @return string
     * @access protected
     */
    abstract protected function realError($resource)/*# : string */;

    /**
     * Statement specific error code
     *
     * @param  mixed $resource
     * @return string
     * @access protected
     */
    abstract protected function realErrorCode($resource)/*# : string */;

    /**
     * Close statement's result set
     *
     * @param  mixed prepared low-level statement
     * @access protected
     */
    abstract protected function realClose($stmt);
}
