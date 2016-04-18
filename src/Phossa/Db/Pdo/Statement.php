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
    protected function realExecute(array $parameters)/*# : bool */
    {
        /* @var $stmt \PDOStatement */
        $stmt = $this->prepared;

        // bind parameters
        if (!empty($parameters) &&
            !$this->bindParameters($stmt, $parameters)
        ) {
            // bind failure
            return false;
        }

        // execute
        return $stmt->execute();
    }

    /**
     * {@inheritDoc}
     */
    protected function realError($resource)/*# : string */
    {
        $error = $resource->errorInfo();
        return $error[2];
    }

    /**
     * {@inheritDoc}
     */
    protected function realErrorCode($resource)/*# : string */
    {
        return $resource->errorCode();
    }

    /**
     * {@inheritDoc}
     */
    protected function realClose($stmt)
    {
        /* @var $stmt \PDOStatement */
        $stmt->closeCursor();
    }

    /**
     * bind parameters
     *
     * @param  \PDOStatement $stmt
     * @param  array $parameters
     * @return bool
     * @access protected
     */
    protected function bindParameters(
        \PDOStatement $stmt,
        array $parameters
    )/*# : bool */ {
        foreach ($parameters as $name => &$value) {
            $type  = Types::guessType($value);
            $param = is_int($name) ? ($name + 1) :
                ($name[0] === ':' ? $name : (':' . $name));
            if (false === $stmt->bindParam($param, $value, $type)) {
                return false;
            }
        }
        return true;
    }
}

