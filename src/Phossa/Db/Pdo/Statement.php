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

namespace Phossa\Db\Pdo;

use Phossa\Db\Types;
use Phossa\Db\Statement\StatementAbstract;

/**
 * Statement
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     StatementAbstract
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Statement extends StatementAbstract
{
    /**
     * Prepared statement
     *
     * @var    \PDOStatement
     * @access protected
     * @staticvar
     */
    protected static $previous_statement;

    /**
     * {@inheritDoc}
     */
    protected function realPrepare($link, /*# string */ $sql)
    {
        /* @var $link \PDO */
        return $link->prepare($sql);
    }

    /**
     * {@inheritDoc}
     */
    protected function realExecute(array $parameters)
    {
        /* @var $stmt \PDOStatement */
        $stmt = $this->prepared;

        // save as previous statement
        if (null !== self::$previous_statement &&
            self::$previous_statement !== $stmt
        ) {
            self::$previous_statement->closeCursor();
        }
        self::$previous_statement = $stmt;

        // execute the statement
        if (!empty($parameters)) {
            $this->bindParameters($stmt, $parameters);
        }

        // execute
        $stmt->execute();
    }

    /**
     * {@inheritDoc}
     */
    protected function realDestruct()
    {
        /* @var $stmt \PDOStatement */
        $stmt = $this->prepared;
        $stmt->closeCursor();
    }

    /**
     * bind parameters
     *
     * @param  \PDOStatement $stmt
     * @param  array $parameters
     * @return this
     * @access protected
     */
    protected function bindParameters(\PDOStatement $stmt, array $parameters)
    {
        foreach ($parameters as $name => &$value) {
            $type  = Types::guessType($value);
            $param = is_int($name) ? ($name + 1) : $name;
            $stmt->bindParam($param, $value, $type);
        }
        return $this;
    }
}

