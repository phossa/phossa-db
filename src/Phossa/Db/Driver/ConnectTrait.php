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
use Phossa\Db\Exception\LogicException;
use Phossa\Db\Exception\InvalidArgumentException;

/**
 * Implementation of ConnectInterface
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ConnectInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
trait ConnectTrait
{
    /**
     * the connection link
     *
     * @var    resource
     * @access protected
     */
    protected $link;

    /**
     * connect parameters
     *
     * @var    array
     * @access protected
     */
    protected $connect_parameters = [];

    /**
     * connection attributes
     *
     * @var    array
     * @access protected
     */
    protected $attributes = [];

    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        if ($this->isConnected()) {
            return $this;
        }

        // connect using parameters
        if (empty($this->connect_parameters)) {
            throw new LogicException(
                Message::get(Message::DB_CONNECT_MISSING),
                Message::DB_CONNECT_MISSING
            );
        } else {
            try {
                $this->link = $this->realConnect($this->connect_parameters);
            } catch (\Exception $e) {
                throw new LogicException(
                    Message::get(Message::DB_CONNECT_FAIL, $e->getMessage()),
                    Message::DB_CONNECT_FAIL
                );
            }
        }

        // set attributes if any
        if (!empty($this->attributes)) {
            foreach ($this->attributes as $attr => $val) {
                $this->realSetAttribute($attr, $val);
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function disconnect()
    {
        if ($this->isConnected()) {
            $this->realDisconnect();
            $this->link = null;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isConnected()/*# : bool */
    {
        return null !== $this->link;
    }

    /**
     * {@inheritDoc}
     */
    public function ping()/*# : bool */
    {
        if ($this->isConnected()) {
            return $this->realPing();
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getLink()
    {
        $this->connect();
        return $this->link;
    }

    /**
     * {@inheritDoc}
     */
    public function setAttribute(/*# int */ $attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        if ($this->isConnected()) {
            $this->realSetAttribute($attribute, $value);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute(/*# int */ $attribute)
    {
        // current attribute
        $curr = isset($this->attributes[$attribute]) ?
            $this->attributes[$attribute] : null;

        if ($this->isConnected()) {
            $real = $this->realGetAttribute($attribute);
            return $real ?: $curr;
        } else {
            return $curr;
        }
    }

    /**
     * Set connect parameters or link
     *
     * @param  array|resource $parameters
     * @return this
     * @throws InvalidArgumentException if link type not right
     * @access protected
     */
    protected function setConnect($parameters)
    {
        try {
            if (is_array($parameters)) {
                $this->connect_parameters = $parameters;
            } elseif (!$this->setConnectLink($parameters)) {
                throw new InvalidArgumentException(
                    Message::get(
                        Message::DB_INVALID_DRIVER,
                        gettype($parameters)
                    ),
                    Message::DB_INVALID_DRIVER
                );
            }
            return $this;
        } catch (\Exception $e) {
            throw new InvalidArgumentException(
                Message::get(
                    Message::DB_INVALID_LINK,
                    $e->getMessage()
                ),
                Message::DB_INVALID_LINK,
                $e
            );
        }
    }

    /**
     * Set the link explicitly
     *
     * @param  resource $link
     * @return bool
     * @access protected
     */
    abstract protected function setConnectLink($link)/*# : bool */;

    /**
     * Driver specific connect
     *
     * @param  array $parameters
     * @return resource
     * @throws LogicException if connect failed
     * @access protected
     */
    abstract protected function realConnect(array $parameters);

    /**
     * Driver specific disconnect
     *
     * @return this
     * @access protected
     */
    abstract protected function realDisconnect();

    /**
     * Driver related ping
     *
     * @return bool
     * @access protected
     */
    abstract protected function realPing()/*# : bool */;

    /**
     * Set connection specific attribute
     *
     * @param  int attribute
     * @param  mixed $value
     * @return $this
     * @access protected
     */
    abstract protected function realSetAttribute(/*# int */ $attribute, $value);

    /**
     * Get connection specific attribute
     *
     * @param  int attribute
     * @return mixed|null
     * @access protected
     */
    abstract protected function realGetAttribute(/*# int */ $attribute);
}
