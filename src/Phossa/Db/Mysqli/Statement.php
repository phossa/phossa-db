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

        // bind parameters
        if (!empty($parameters) &&
            !$this->bindParameters($stmt, $parameters)
        ) {
            // bind failure
            return false;
        }

        $res = $stmt->execute();

        if ($stmt->result_metadata()) {
            $stmt->store_result();
        }

        return $res;
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
     * {@inheritDoc}
     */
    protected function realClose($stmt)
    {
        /* @var $stmt \mysqli_stmt */
        $stmt->close();
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
        $args  = [];
        foreach ($parameters as $name => &$value) {
            $type = Types::guessType($value);
            switch ($type) {
                case Types::PARAM_INT:
                case Types::PARAM_BOOL:
                    $types .= 'i';
                    break;
                default:
                    $types .= 's';
                    break;
            }
            $args[] = &$value;
        }
        if (count($args)) {
            array_unshift($args, $types);
            return call_user_func_array([$stmt, 'bind_param'], $args);
        }
        return true;
    }
}
