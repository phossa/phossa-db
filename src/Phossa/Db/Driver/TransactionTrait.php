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

use Phossa\Db\Message\Message;
use Phossa\Db\Exception\RuntimeException;

/**
 * Implementation of TransactionInterface
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     TransactionInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
trait TransactionTrait
{
    /**
     * In transaction or not
     *
     * @var    bool
     * @access protected
     */
    protected $transaction = false;

    /**
     * {@inheritDoc}
     */
    public function inTransaction()/*# : bool */
    {
        return $this->transaction;
    }

    /**
     * {@inheritDoc}
     */
    public function begin()
    {
        $this->connect();
        $this->transaction = true;
        return $this->realBegin();
    }

    /**
     * {@inheritDoc}
     */
    public function commit()
    {
        if ($this->isConnected()) {
            $this->realCommit();
        }
        $this->transaction = false;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function rollback()
    {
        if ($this->isConnected()) {
            if (!$this->transaction) {
                throw new RuntimeException(
                    Message::get(Message::DB_INVALID_ROLLBACK),
                    Message::DB_INVALID_ROLLBACK
                );
            }
            $this->realRollback();
        }
        $this->transaction = false;
        return $this;
    }

    /**
     * Driver specific begin transaction
     *
     * @return this
     * @access protected
     */
    abstract protected function realBegin();

    /**
     * Driver specific commit
     *
     * @return this
     * @access protected
     */
    abstract protected function realCommit();

    /**
     * Driver specific rollback
     *
     * @return this
     * @access protected
     */
    abstract protected function realRollback();

    /* from other traits */
    abstract public function connect();
    abstract public function isConnected();
}
