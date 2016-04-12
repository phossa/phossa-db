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

namespace Phossa\Db\Mysqli;

use Phossa\Db\Types;
use Phossa\Db\Statement\StatementAbstract;

/**
 * Mysqli statement
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
     * @var    \mysqli_stmt
     * @access protected
     * @staticvar
     */
    protected static $previous_statement;

    /**
     * {@inheritDoc}
     */
    protected function realPrepare($link, /*# string */ $sql)
    {
        /* @var $link \mysqli */
        return $link->prepare($sql);
    }

    /**
     * {@inheritDoc}
     */
    protected function realExecute(array $parameters)/*# : bool */
    {
        /** @var $stmt \mysqli_stmt */
        $stmt = $this->prepared;

        // save as previous statement
        if (null !== self::$previous_statement &&
            self::$previous_statement !== $stmt
        ) {
            self::$previous_statement->free_result();
            self::$previous_statement->close();
        }
        self::$previous_statement = $stmt;

        // bind parameters
        if (!empty($parameters) &&
            !$this->bindParameters($stmt, $parameters)
        ) {
            // bind failure
            return false;
        }

        return $stmt->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function realDestruct()
    {
        $this->prepared->free_result();
        $this->prepared->close();
    }

    /**
     * {@inheritDoc}
     */
    protected function realError($resource)/*# : string */
    {
        return $resource->error;
    }

    /**
     * {@inheritDoc}
     */
    protected function realErrorCode($resource)/*# : string */
    {
        return $resource->errno;
    }

    /**
     * bind parameters
     *
     * @param  \mysqli_stmt $stmt
     * @param  array $parameters
     * @return bool
     * @access protected
     */
    protected function bindParameters(
        \mysqli_stmt $stmt,
        array $parameters
    )/*# : bool */ {
        $types = '';
        foreach ($parameters as $name => $value) {
            $type  = Types::guessType($value);
            switch ($type) {
                case Types::PARAM_INT:
                    $types .= 'i';
                    break;
                default:
                    $types .= 's';
                    break;
            }
        }
        return $stmt->bind_param($types, $parameters);
    }
}