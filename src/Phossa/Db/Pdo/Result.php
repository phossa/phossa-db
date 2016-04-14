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

use Phossa\Db\Result\ResultAbstract;

/**
 * PDO result
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
     * @var    \PDOStatement
     * @access protected
     */
    protected $statement;

    /**
     * Invoke to set statement
     *
     * @param  \PDOStatement $statement
     * @return this
     * @access public
     */
    public function __invoke(\PDOStatement $statement)
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function fieldCount()/*# : int */
    {
        return $this->statement->columnCount();
    }

    /**
     * {@inheritDoc}
     */
    public function rowCount()/*# : int */
    {
        if ($this->statement) {
            return $this->statement->rowCount();
        }
        return 0;
    }

    /**
     * {@inheritDoc}
     */
    public function affectedRows()/*# : int */
    {
        return $this->statement->rowCount();
    }

    /**
     * {@inheritDoc}
     */
    protected function realFetchAll()/*# : array */
    {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * {@inheritDoc}
     */
    protected function realFetchRow($rowCount)/*# : array */
    {
        $result = [];
        $count  = 0;
        while ($count++ < $rowCount) {
            $row = $this->statement->fetch(\PDO::FETCH_ASSOC);
            if (false === $row) {
                break;
            }
            $result[] = $row;
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    protected function realDestruct()
    {
        if ($this->statement) {
            $this->statement->closeCursor();
        }
    }
}
