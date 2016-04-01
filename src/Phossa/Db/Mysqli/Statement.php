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

use Phossa\Db\Result\ResultInterface;
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
     * statement object
     *
     * @var    \mysqli_stmt
     * @access protected
     */
    protected $stmt;

    /**
     * close statement
     *
     * @access public
     */
    public function __destruct()
    {
        if ($this->stmt) {
            $this->stmt->close();
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function realPrepare(
        $link,
        /*# string */ $sql
    )/*# : bool */ {
        $this->stmt = null;

        /** @var $link \mysqli */
        $stmt = $link->prepare($sql);
        if ($stmt) {
            $this->stmt = $stmt;
            return true;
        }

        // failed
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function realExecute(
        array $parameters,
        ResultInterface $result
    )/*# : ResultInterface */ {

    }
}

