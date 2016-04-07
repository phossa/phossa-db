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

use Phossa\Db\Message\Message;
use Phossa\Db\Driver\ErrorTrait;
use Phossa\Db\Exception\RuntimeException;

/**
 * Result
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ResultInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
abstract class ResultAbstract implements ResultInterface
{
    use ErrorTrait;

    /**
     * {@inheritDoc}
     */
    public function isQuery()/*# : bool */
    {
        $this->exceptionIfNotSuccess();
        return 0 !== $this->fieldCount();
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAll()/*# : array */
    {
        $this->exceptionIfNotQuery();
        return $this->realFetchAll();
    }

    /**
     * {@inheritDoc}
     */
    public function fetchRow(/*# int */ $rowCount = 1)/*# : array */
    {
        $this->exceptionIfNotQuery();
        return $this->realFetchRow($rowCount);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchCol($col = 0, $rowCount = 1)/*# : array */
    {
        if ($rowCount) {
            $rows = $this->fetchRow($rowCount);
        } else {
            $rows = $this->fetchAll();
        }

        $cols = $keys = [];
        foreach($rows as $row) {
            // named or indexed column
            if (isset($row[$col])) {
                $cols[] = $row[$col];

            // n'th column
            } elseif (is_int($col) && count($row) >= $col) {
                if (empty($keys)) {
                    $keys = array_keys($row);
                }
                $cols[] = $row[$keys[$col]];
            }
        }

        return $cols;
    }

    /**
     * Throw exception if not select query
     *
     * @throws RuntimeException if not a select query
     * @access protected
     */
    protected function exceptionIfNotQuery()
    {
        // not a valid result
        $this->exceptionIfNotSuccess();

        // not a SELECT query
        if (!$this->isQuery()) {
            throw new RuntimeException(
                Message::get(Message::DB_SQL_NOT_QUERY),
                Message::DB_SQL_NOT_QUERY
            );
        }
    }

    /**
     * Throw exception if not a successful result
     *
     * @throws RuntimeException if not successful get a result
     * @access protected
     */
    protected function exceptionIfNotSuccess()
    {
        // not a valid result
        if (!$this->isSuccessful()) {
            throw new RuntimeException(
                Message::get(Message::DB_INVALID_RESULT),
                Message::DB_INVALID_RESULT
                );
        }
    }

    /**
     * Driver fetch all
     *
     * @return array
     * @access protected
     */
    abstract protected function realFetchAll()/*# : array */;

    /**
     * Driver fetch row
     *
     * @param  int $rowCount number of rows to fetch
     * @return array
     * @access protected
     */
    abstract protected function realFetchRow($rowCount)/*# : array */;
}
