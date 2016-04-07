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
 * ErrorInterface
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface ErrorInterface
{
    /**
     * Is operation successful
     *
     * @return bool
     * @access public
     */
    public function isSuccessful()/*# : bool */;

    /**
     * Get low-level error code
     *
     * @return int
     * @access public
     */
    public function getErrorCode()/*# : int */;

    /**
     * Get low-level error info
     *
     * @return string
     * @access public
     */
    public function getError()/*# : string */;
}
