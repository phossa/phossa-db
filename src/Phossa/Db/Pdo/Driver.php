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

namespace Phossa\Db\Pdo;

use Phossa\Db\Message\Message;
use Phossa\Db\Driver\DriverAbstract;
use Phossa\Db\Result\ResultInterface;
use Phossa\Db\Exception\LogicException;
use Phossa\Db\Statement\StatementInterface;

/**
 * PDO driver
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
     * the connection link
     *
     * @var    \PDO
     * @access protected
     */
    protected $link;

    /**
     * Default PDO attributes
     *
     * @var    array
     * @access protected
     */
    protected $attributes = [
        'PDO::ATTR_ERRMODE'             => \PDO::ERRMODE_SILENT,
        'PDO::ATTR_CASE'                => \PDO::CASE_NATURAL,
        'PDO::ATTR_ORACLE_NULLS'        => \PDO::NULL_NATURAL,
        'PDO::ATTR_DEFAULT_FETCH_MODE'  => \PDO::FETCH_ASSOC,
        'PDO::ATTR_EMULATE_PREPARES'    => false,
    ];

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
        if ($this->isConnected()) {
            return strtolower($this->link->getAttribute(\PDO::ATTR_DRIVER_NAME));
        } else {
            return 'pdo';
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function extensionLoaded()/*# : bool */
    {
        return extension_loaded('PDO');
    }

    /**
     * {@inheritDoc}
     */
    protected function realLastId($name)
    {
        return $this->link->lastInsertId($name);
    }

    /**
     * {@inheritDoc}
     */
    protected function realQuote(
        $string,
        /*# int */ $type
    )/*# : string */ {
        return $this->link->quote($string, $type);
    }

    /**
     * Set the \PDO link
     *
     * {@inheritDoc}
     */
    protected function setConnectLink($link)/*# : bool */
    {
        if ($link instanceof \PDO) {
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
        $this->link = new \PDO(
            $parameters['dsn'],
            isset($parameters['username']) ? $parameters['username'] : 'root',
            isset($parameters['password']) ? $parameters['password'] : null,
            isset($parameters['options']) ? $parameters['options'] : null
        );

        // set attributes
        if (!empty($this->attributes)) {
            foreach ($this->attributes as $attr => $val) {
                $this->realSetAttribute($attr, $val);
            }
        }

        return $this;
    }

    /**
     * Disconnect the \PDO link
     *
     * {@inheritDoc}
     */
    protected function realDisconnect()
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realPing()/*# : bool */
    {
        try {
            return (bool) $this->link->query('SELECT 1');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function realSetAttribute(/*# string */ $attribute, $value)
    {
        if (is_string($attribute)) {
            if (defined($attribute)) {
                $this->link->setAttribute(constant($attribute), $value);
            } else {
                throw new LogicException(
                    Message::get(Message::DB_UNKNOWN_ATTRIBUTE, $attribute),
                    Message::DB_UNKNOWN_ATTRIBUTE
                );
            }
        } else {
            $this->link->setAttribute($attribute, $value);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realGetAttribute(/*# string */ $attribute)
    {
        if (is_string($attribute)) {
            if (defined($attribute)) {
                return $this->link->getAttribute(constant($attribute));
            } else {
                throw new LogicException(
                    Message::get(Message::DB_UNKNOWN_ATTRIBUTE, $attribute),
                    Message::DB_UNKNOWN_ATTRIBUTE
                );
            }
        } else {
            return $this->link->getAttribute($attribute);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function realBegin()
    {
        $this->link->beginTransaction();
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realCommit()
    {
        $this->link->commit();
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realRollback()
    {
        $this->link->rollBack();
        return $this;
    }
}
