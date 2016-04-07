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

namespace Phossa\Db\Result;

use Phossa\Db\Driver\ErrorInterface;
use Phossa\Db\Exception\RuntimeException;

/**
 * ResultInterface
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface ResultInterface extends ErrorInterface
{
    /**
     * Is this a query (SELECT) result
     *
     * @return bool
     * @access public
     */
    public function isQuery()/*# : bool */;

    /**
     * Get field count of the result
     *
     * @return int
     * @access public
     */
    public function fieldCount()/*# : int */;

    /**
     * Get row count of the query result
     *
     * @return int
     * @access public
     */
    public function rowCount()/*# : int */;

    /**
     * Get affected row count for DDL statement
     *
     * @return int
     * @access public
     */
    public function affectedRows()/*# : int */;

    /**
     * Fetch all the result rows
     *
     * @return array
     * @throws RuntimeException if not a query
     * @access public
     */
    public function fetchAll()/*# : array */;

    /**
     * Fetch first n'th rows
     *
     * @param  int $rowCount
     * @return array
     * @throws RuntimeException if not a query
     * @access public
     */
    public function fetchRow(/*# int */ $rowCount = 1)/*# : array */;

    /**
     * Fetch the named/positioned field of the first # of rows
     *
     * if $rowCount == 0, fetch col of all the result rows
     *
     * @param  int|string $col position or column name
     * @param  int $rowCount if > 1, fetch $rowCount rows
     * @return string|array
     * @throws RuntimeException if not a query
     * @access public
     */
    public function fetchCol($col = 0, $rowCount = 1)/*# : array */;
}
