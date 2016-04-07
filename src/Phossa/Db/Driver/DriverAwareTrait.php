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

/**
 * DriverAwareTrait
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverAwareInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
trait DriverAwareTrait
{
    /**
     * the driver
     *
     * @var    DriverInterface
     * @access protected
     */
    protected $driver;

    /**
     * {@inheritDoc}
     */
    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDriver()/*# : DriverInterface */
    {
        if (null === $this->driver) {
            throw new LogicException(
                Message::get(Message::DB_SQL_NO_DRIVER),
                Message::DB_SQL_NO_DRIVER
            );
        }
        return $this->driver;
    }
}
