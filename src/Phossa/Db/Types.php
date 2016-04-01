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

namespace Phossa\Db;

/**
 * Parameter types
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Types
{
    /**#@+
     * Parameter types
     *
     * @var   int
     */

    /**
     * null
     */
    const PARAM_NULL        = \PDO::PARAM_NULL;

    /**
     * integer
     */
    const PARAM_INT         = \PDO::PARAM_INT;

    /**
     * string
     */
    const PARAM_STR         = \PDO::PARAM_STR;

    /**
     * lob
     */
    const PARAM_LOB         = \PDO::PARAM_LOB;

    /**
     * statement
     */
    const PARAM_STMT        = \PDO::PARAM_STMT;

    /**
     * boolean
     */
    const PARAM_BOOL        = \PDO::PARAM_BOOL;

    /**#@-*/

}
