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

use Phossa\Db\Exception\LogicException;

/**
 * DriverAwareInterface
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface DriverAwareInterface
{
    /**
     * Set the driver
     *
     * @param  DriverInterface $driver
     * @return this
     * @access public
     */
    public function setDriver(DriverInterface $driver);

    /**
     * Get the driver
     *
     * @return value
     * @throws LogicException if driver not set
     * @access public
     */
    public function getDriver()/*# : DriverInterface */;
}
