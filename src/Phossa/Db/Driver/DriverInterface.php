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

use Phossa\Db\Types;
use Phossa\Db\Result\ResultInterface;
use Phossa\Db\Exception\LogicException;
use Phossa\Db\Statement\StatementInterface;
use Phossa\Shared\Error\ErrorAwareInterface;

/**
 * DriverInterface
 *
 * @package \Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface DriverInterface extends ConnectInterface, TransactionInterface, ErrorAwareInterface
{
    /**
     * Prepare the sql and get the statement object or FALSE on failure
     *
     * @param  string $sql SQL statement
     * @return StatementInterface|false
     * @throws LogicException if connect failed
     * @access public
     */
    public function prepare(/*# string */ $sql);

    /**
     * Execute DDL statement
     *
     * Get affected row number or FALSE on failure
     *
     * @param  string $sql
     * @param  array $parameters
     * @return int|false
     * @throws LogicException if connect failed
     * @access public
     */
    public function execute(/*# string */ $sql, array $parameters = []);

    /**
     * Execute the sql with given parameters
     *
     * Return the result object or FALSE on failure
     *
     * @param  string $sql SQL statement
     * @param  array $parameters
     * @return ResultInterface|false
     * @throws LogicException if connect failed
     * @access public
     */
    public function query(/*# string */ $sql, array $parameters = []);

    /**
     * Quote the $string
     *
     * @param  bool|int|string|null $string
     * @param  int $type data type
     * @return string
     * @throws LogicException if connect failed
     * @access public
     */
    public function quote(
        $string,
        /*# int */ $type = Types::PARAM_STR
    )/*# : string */;

    /**
     * Get underlying driver name in lowercase
     *
     * @return string
     * @access public
     */
    public function getDriverName()/*# : string */;

    /**
     * Get last insert id
     *
     * @param  string $name sequence name if any
     * @return string|null
     * @access public
     */
    public function getLastInsertId($name = null);
}
