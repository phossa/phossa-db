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

namespace Phossa\Db\Driver;

use Phossa\Db\Types;
use Phossa\Db\Message\Message;
use Phossa\Db\Result\ResultInterface;
use Phossa\Db\Exception\LogicException;
use Phossa\Shared\Taggable\TaggableTrait;
use Phossa\Db\Statement\StatementInterface;
use Phossa\Shared\Taggable\TaggableInterface;
use Phossa\Db\Exception\InvalidArgumentException;

/**
 * DriverAbstract
 *
 * Driver with tag supported. In this case, you can tag with 'ReadOnly' etc.
 *
 * @abstract
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverInterface
 * @see     TaggableInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
abstract class DriverAbstract implements DriverInterface, TaggableInterface
{
    use TaggableTrait, ConnectTrait, TransactionTrait;

    /**
     * Statement prototype
     *
     * @var    StatementInterface
     * @access protected
     */
    protected $statement;

    /**
     * Result prototype
     *
     * @var    ResultInterface
     * @access protected
     */
    protected $result;

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
    abstract public function __construct(
        $connectInfo,
        StatementInterface $statementPrototype = null,
        ResultInterface $resultPrototype = null
    );


    /**
     * {@inheritDoc}
     */
    public function prepare(/*# string */ $sql)/*# : StatementInterface */
    {
        // statement
        $statement = clone $this->statement;

        // use $statement->isPrepared() to see ok or not
        return $statement->init($this, $this->result)->prepare($sql);
    }

    /**
     * {@inheritDoc}
     */
    public function execute(/*# string */ $sql, array $parameters = [])
    {
        $result = $this->query($sql, $parameters);
        if ($result->isSuccessful()) {
            return $result->affectedRows();
        } else {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function query(
        /*# string */ $sql,
        array $parameters = []
    )/*# : ResultInterface */ {
        return $this->prepare($sql)->execute($parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function quote(
        $string,
        /*# int */ $type = Types::PARAM_STR
    )/*# : string */ {
        // try connect first
        $this->connect();

        // types we don't quote
        if (is_null($string)) {
            return 'NULL';
        } elseif (is_bool($string)) {
            return $string ? 'TRUE' : 'FALSE';
        } elseif (is_int($string)) {
            return (string) $string;
        }

        // string remained
        switch($type) {
            case Types::PARAM_NULL:
                return 'NULL';
            case Types::PARAM_STMT:
                return (string) $string;
            case Types::PARAM_INT:
                return (string)(int) $string;
            case Types::PARAM_BOOL:
                $res = strtolower($string);
                if ('' === $res) {
                    return 'FALSE';
                } else {
                    return 'f' === $res[0] ? 'FALSE' : 'TRUE';
                }
            default :
                return $this->realQuote((string) $string, $type);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getLastInsertId($name = null)
    {
        if ($this->isConnected()) {
            return $this->realLastId($name);
        }
        return null;
    }

    /**
     * Set up connection or connection parameters. called by constructor
     *
     * @param  array|resource $connectInfo
     * @throws InvalidArgumentException if link type not right
     * @throws LogicException driver specific extension not loaded
     * @access protected
     */
    protected function init($connectInfo)
    {
        // check driver specific extension
        if (!$this->extensionLoaded()) {
            throw new LogicException(
                Message::get(Message::DB_EXTENSION_NOT_LOAD, get_class($this)),
                Message::DB_EXTENSION_NOT_LOAD
            );
        }

        // set connect
        $this->setConnect($connectInfo);
    }

    /**
     * Check driver specific extension loaded or not
     *
     * @return bool
     * @access protected
     */
    abstract protected function extensionLoaded()/*# : bool */;

    /**
     * Driver specific last inserted id
     *
     * @param  string $name sequence name
     * @return string|null
     * @access protected
     */
    abstract protected function realLastId(/*# string */ $name);

    /**
     * Driver specific quote
     *
     * @param  string $string
     * @param  int $type parameter type
     * @return string
     * @access protected
     */
    abstract protected function realQuote(
        /*# string */ $string,
        /*# int */ $type
    )/*# : string */;
}
