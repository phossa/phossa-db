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
     * mysqli link
     *
     * @var    \mysqli
     * @access protected
     */
    protected $link;

    /**
     * mysql result
     *
     * @var    \mysqli_result
     * @access protected
     */
    protected $result;

    /**
     * Constructor
     *
     * @param  \mysqli $link
     * @param  \mysqli_result $result optional result object
     * @access public
     */
    public function __construct(
        \mysqli $link = null,
        \mysqli_result $result = null
    ) {
        if ($link) {
            $this->link = $link;
        }

        if ($result) {
            $this->result = $result;
        }
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
     * Invoke to set link and result
     *
     * @param  \mysqli $link
     * @param  \mysqli_result $result
     * @return this
     * @access public
     */
    public function __invoke(
        \mysqli $link,
        \mysqli_result $result
    ) {
        $this->link = $link;
        $this->result = $result;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getErrorCode()/*# : int */
    {
        return $this->link->errno;
    }

    /**
     * {@inheritDoc}
     */
    public function getError()/*# : string */
    {
        return $this->link->error;
    }

    /**
     * {@inheritDoc}
     */
    public function fieldCount()/*# : int */
    {
        return $this->link->field_count;
    }

    /**
     * {@inheritDoc}
     */
    public function rowCount()/*# : int */
    {
        if ($this->result) {
            return $this->result->num_rows;
        }
        return 0;
    }

    /**
     * Get affected row count for DDL statement
     *
     * @return int
     * @access public
     */
    public function affectedRows()/*# : int */
    {
        return $this->link->affected_rows;
    }

    /**
     * {@inheritDoc}
     */
    protected function realFetchAll()/*# : array */
    {
        return $this->result->fetch_all(\MYSQLI_ASSOC);
    }

    /**
     * {@inheritDoc}
     */
    protected function realFetchRow($rowCount)/*# : array */
    {
        $result = [];
        $count  = 0;
        $this->result->data_seek(0);
        while ($count++ < $rowCount) {
            $data = $this->result->fetch_assoc();
            if (null === $data) {
                break;
            }
            $result[] = $data;
        }
        return $result;
    }
}
