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

/**
 * Transaction related
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface TransactionInterface
{
    /**
     * Is in transaction
     *
     * @return bool
     * @access public
     */
    public function inTransaction()/*# : bool */;

    /**
     * Begin transaction
     *
     * @return this
     * @throws LogicException if connect failed
     * @access public
     */
    public function begin();

    /**
     * Commit transaction
     *
     * @return this
     * @access public
     */
    public function commit();

    /**
     * Rollback transaction
     *
     * @return this
     * @throws RuntimeException called before begin transaction
     * @access public
     */
    public function rollback();
}
