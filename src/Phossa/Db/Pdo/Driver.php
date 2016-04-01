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

use Phossa\Db\Driver\DriverAbstract;

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
     * Default PDO attributes
     *
     * @var    array
     * @access protected
     */
    protected $attributes = [

    ];

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
    protected function realLastId(/*# string */ $name)
    {
        return $this->link->lastInsertId($name);
    }

    /**
     * {@inheritDoc}
     */
    protected function realQuote(
        /*# string */ $string,
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
        // @todo
        $this->link = new \PDO();

        // preset attributes
        if (!empty($this->attributes)) {

        }

        return $this;
    }

    /**
     * Disconnect the \PDO link
     *
     * {@inheritDoc}
     */
    protected function disconnectLink()
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realPing()/*# : bool */
    {
        try {
            $this->link->query('SELECT 1');
        } catch (\PDOException $e) {
            return false;
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function realSetAttribute(/*# int */ $attribute, $value)
    {
        $this->link->setAttribute($attribute, $value);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realGetAttribute(/*# int */ $attribute)
    {
        return $this->link->getAttribute($attribute);
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
