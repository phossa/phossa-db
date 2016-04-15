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

namespace Phossa\Db\Statement;

use Phossa\Db\Result\ResultInterface;
use Phossa\Db\Exception\LogicException;
use Phossa\Db\Exception\RuntimeException;

/**
 * StatementInterface
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface StatementInterface
{
    /**
     * Prepare the SQL statement
     *
     * @param  string $sql
     * @return bool
     * @throws LogicException if connect fail or no driver set
     * @throws RuntimeException if prepared twice
     * @access public
     */
    public function prepare(/*# string */ $sql)/*# : bool */;

    /**
     * Execute the prepared statement and return the result
     *
     * @param  array $parameters
     * @return ResultInterface|false
     * @access public
     */
    public function execute(array $parameters = []);

    /**
     * Get previous executiion time
     *
     * @return float
     * @access public
     */
    public function getExecutionTime()/*# : float */;
}
