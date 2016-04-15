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

use Phossa\Db\Message\Message;
use Phossa\Db\Driver\DriverInterface;
use Phossa\Db\Exception\NotFoundException;
use Phossa\Shared\Taggable\TaggableInterface;

/**
 * Implementation of ManagerInterface
 *
 * - Added 'weight factor' for round-robin fashion of load balancing
 * - If driver supports tags, may pick driver by tag
 *
 * ```php
 * $dbm = new Manager();
 *
 * // db writer with weighting factor 1
 * $dbm->addDriver($driver1->addTag('RW'), 1);
 *
 * // db reader with weighting factor 5
 * $dbm->addDriver($driver2->addTag('RO'), 5);
 *
 * // get whatever reader or writer
 * $db = $dbm->getDriver();
 *
 * // get read_only driver
 * $dbReader = $dbm->getDriver('RO');
 * ```
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ManagerInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Manager implements ManagerInterface
{
    /**
     * drivers
     *
     * @var    DriverInterface[]
     * @access protected
     */
    protected $drivers = [];

    /**
     * driver weight factor
     *
     * @var    array
     * @access protected
     */
    protected $factors = [];

    /**
     * Specify a weighting factor for the driver. normally 1 - 10
     *
     * {@inheritDoc}
     * @param  int $factor weight factor for round-robin
     */
    public function addDriver(DriverInterface $driver, /*# int */ $factor = 1)
    {
        // get unique driver id
        $id = $this->getDriverId($driver);

        // fix factor range: 1 - 10
        $this->factors[$id] = (int) (
            $factor > 10 ? 10 : ($factor < 1 ? 1 : $factor)
        );

        // add to the pool
        $this->drivers[$id] = $driver;

        return $this;
    }

    /**
     * Get a driver with a tag matched
     *
     * {@inheritDoc}
     */
    public function getDriver(/*# string */ $tag = '')/*# : DriverInterface */
    {
        // match drivers
        $matched = $this->driverMatcher($tag);

        // match found
        if (count($matched) > 0) {
            // pick a random driver
            return $this->drivers[$matched[rand(1, count($matched)) - 1]];

        // not found
        } else {
            throw new NotFoundException(
                Message::get(Message::DB_DRIVER_NOTFOUND),
                Message::DB_DRIVER_NOTFOUND
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function removeDriver(DriverInterface $driver)
    {
        $id = $this->getDriverId($driver);
        if (isset($this->drivers[$id])) {
            unset($this->drivers[$id]);
            unset($this->factors[$id]);
        }
        return $this;
    }

    /**
     * Return a unique string id of the driver
     *
     * @param  DriverInterface $driver
     * @return string
     * @access protected
     */
    protected function getDriverId(DriverInterface $driver)/*# : string */
    {
        return spl_object_hash($driver);
    }

    /**
     * Driver tag matcher
     *
     * @param  string $tag tag to match
     * @return array
     * @access protected
     */
    protected function driverMatcher(/*# string */ $tag)/*# : array */
    {
        $matched = [];
        foreach ($this->drivers as $id => $driver) {
            // disconnected or tag not match
            if (!$driver->ping() ||
                '' !== $tag &&
                (
                    !$driver instanceof TaggableInterface ||
                    !$driver->hasTag($tag)
                )
            ) {
                continue;
            }

            // weight factor
            $f = $this->factors[$id];
            for ($i = 0; $i < $f; ++$i) {
                $matched[] = $id;
            }
        }
        return $matched;
    }
}
