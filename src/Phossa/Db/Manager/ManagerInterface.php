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

namespace Phossa\Db\Manager;

use Phossa\Db\Driver\DriverInterface;
use Phossa\Db\Exception\NotFoundException;

/**
 * Driver & connection manager
 *
 * @package \Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface ManagerInterface
{
    /**
     * Add a driver to the pool
     *
     * @param  DriverInterface $driver
     * @return this
     * @access public
     */
    public function addDriver(DriverInterface $driver);

    /**
     * Get a matched driver
     *
     * @return DriverInterface
     * @throws NotFoundException
     * @access public
     */
    public function getDriver()/*# : DriverInterface */;

    /**
     * Remove the specified driver from the pool
     *
     * @param  DriverInterface $driver
     * @return this
     * @access public
     */
    public function removeDriver(DriverInterface $driver);
}
