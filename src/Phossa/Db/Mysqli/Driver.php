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

namespace Phossa\Db\Mysqli;

use Phossa\Db\Message\Message;
use Phossa\Db\Driver\DriverAbstract;
use Phossa\Db\Result\ResultInterface;
use Phossa\Db\Exception\LogicException;
use Phossa\Db\Statement\StatementInterface;

/**
 * Mysqli driver
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverAbstract
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Driver extends DriverAbstract
{
    /**
     * Driver constructor
     *
     * @param  array|resource $connectInfo
     * @param  StatementInterface $statementPrototype
     * @param  ResultInterface $resultPrototype
     * @throws InvalidArgumentException if link type not right
     * @throws LogicException driver specific extension not loaded
     * @access public
     */
    public function __construct(
        $connectInfo,
        StatementInterface $statementPrototype = null,
        ResultInterface $resultPrototype = null
    ) {
        parent::__construct($connectInfo);

        // set prototypes
        $this->statement_prototype = $statementPrototype ?: new Statement();
        $this->result_prototype = $resultPrototype ?: new Result();
    }

    /**
     * {@inheritDoc}
     */
    public function getDriverName()/*# : string */
    {
        return 'mysqli';
    }

    /**
     * {@inheritDoc}
     */
    protected function extensionLoaded()/*# : bool */
    {
        return extension_loaded('mysqli');
    }

    /**
     * {@inheritDoc}
     */
    protected function realLastId(/*# string */ $name)
    {
        return $this->link->insert_id;
    }

    /**
     * {@inheritDoc}
     */
    protected function realQuote(
        /*# string */ $string,
        /*# int */ $type
    )/*# : string */ {
        return '\'' . $this->link->real_escape_string($string) . '\'';
    }

    /**
     * Set the \mysqli link
     *
     * {@inheritDoc}
     */
    protected function setConnectLink($link)/*# : bool */
    {
        if ($link instanceof \mysqli) {
            $this->link = $link;
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function realConnect(array $parameters)
    {
        // init
        $this->link = new \mysqli();
        $this->link->init();

        // set driver specific options
        if (!empty($parameters['options'])) {
            foreach ($parameters['options'] as $option => $value) {
                if (is_string($option)) {
                    $option = strtoupper($option);
                    if (!defined($option)) {
                        continue;
                    }
                    $option = constant($option);
                }
                $this->link->options($option, $value);
            }
        }

        // real connect
        $this->link->real_connect(
            isset($parameters['host']) ? $parameters['host'] : 'localhost',
            isset($parameters['user']) ? $parameters['user'] : 'root',
            isset($parameters['password']) ? $parameters['password'] : null,
            isset($parameters['db']) ? $parameters['db'] : null,
            isset($parameters['port']) ? (int) $parameters['port'] : null,
            isset($parameters['socket']) ? $parameters['socket'] : null
        );

        if ($this->link->connect_error) {
            throw new LogicException(
                Message::get(
                    Message::DB_CONNECT_FAIL,
                    $this->link->connect_errno,
                    $this->link->connect_error
                ),
                Message::DB_CONNECT_FAIL
            );
        }

        // set charset
        if (!empty($parameters['charset'])) {
            $this->link->set_charset($parameters['charset']);
        }

        return $this;
    }

    /**
     * Disconnect the \mysqli link
     *
     * {@inheritDoc}
     */
    protected function realDisconnect()
    {
        $this->link->close();
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realPing()/*# : bool */
    {
        return $this->link->ping();
    }

    /**
     * {@inheritDoc}
     */
    protected function realSetAttribute(/*# int */ $attribute, $value)
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realGetAttribute(/*# int */ $attribute)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    protected function realErrorCode()/*# : int */
    {
        $this->link->errorCode();
    }

    /**
     * {@inheritDoc}
     */
    protected function realError()/*# : string */
    {
        $error = $this->link->errorInfo();
        return $error[2];
    }

    /**
     * {@inheritDoc
     */
    protected function realBegin()
    {
        $this->link->autocommit(false);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realCommit()
    {
        $this->link->commit();
        $this->link->autocommit(true);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realRollback()
    {
        $this->link->rollback();
        $this->link->autocommit(true);
        return $this;
    }
}
