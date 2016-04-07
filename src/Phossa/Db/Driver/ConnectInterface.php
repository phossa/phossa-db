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
 * Connection related
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ErrorInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface ConnectInterface extends ErrorInterface
{
    /**
     * Connect to the db
     *
     * @return this
     * @throws LogicException if connect failed
     * @access public
     */
    public function connect();

    /**
     * Disconnect with the db
     *
     * @return this
     * @access public
     */
    public function disconnect();

    /**
     * Is connection established
     *
     * @return bool
     * @access public
     */
    public function isConnected()/*# : bool */;

    /**
     * Is connection alive
     *
     * @return bool
     * @access public
     */
    public function ping()/*# : bool */;

    /**
     * Get the connection link
     *
     * @return resource
     * @throws LogicException if connect failed
     * @access public
     */
    public function getLink();

    /**
     * Set connection specific attribute
     *
     * @param  int attribute
     * @param  mixed $value
     * @return this
     * @access public
     */
    public function setAttribute(/*# int */ $attribute, $value);

    /**
     * Get connection specific attribute
     *
     * @param  int attribute
     * @return mixed
     * @access public
     */
    public function getAttribute(/*# int */ $attribute);
}
