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

use Phossa\Db\Result\ResultAbstract;

/**
 * Mysqli result
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ResultAbstract
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Result extends ResultAbstract
{
    /**
     * mysql statement
     *
     * @var    \mysqli_stmt
     * @access protected
     */
    protected $statement;

    /**
     * Invoke to set statement
     *
     * @param  \mysqli_stmt $statement
     * @return this
     * @access public
     */
    public function __invoke(\mysqli_stmt $statement)
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * Destruct
     *
     * @access public
     */
    public function __destruct()
    {
        if ($this->result) {
            $this->result->free_result();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function fieldCount()/*# : int */
    {
        return $this->statement->field_count;
    }

    /**
     * {@inheritDoc}
     */
    public function rowCount()/*# : int */
    {
        return $this->statement->num_rows();
    }

    /**
     * {@inheritDoc}
     */
    public function affectedRows()/*# : int */
    {
        return $this->statement->affected_rows;
    }

    /**
     * {@inheritDoc}
     */
    protected function realFetchAll()/*# : array */
    {
        $mysqli_result = $this->statement->get_result();
        $rows = $mysqli_result->fetch_all(\MYSQLI_ASSOC);
        $mysqli_result->close();
        return $rows;
    }

    /**
     * {@inheritDoc}
     */
    protected function realFetchRow($rowCount)/*# : array */
    {
        $mysqli_result = $this->statement->get_result();
        $result = [];
        $count  = 0;
        $mysqli_result->data_seek(0);
        while ($count++ < $rowCount) {
            $data = $mysqli_result->fetch_assoc();
            if (null === $data) {
                break;
            }
            $result[] = $data;
        }
        $mysqli_result->close();
        return $result;
    }
}
