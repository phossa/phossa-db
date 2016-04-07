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
     * {@inheritDoc}
     */
    protected function realPrepare(/*# string */ $sql)
    {
        return $this->link->prepare($sql);
    }

    /**
     * {@inheritDoc}
     */
    protected function realExecute(array $parameters, ResultInterface $result)
    {
        /** @var $stmt \mysqli_stmt */
        $stmt = $this->prepared;

        if (!empty($parameters)) {
            $stmt->bind_param("s*",  $parameters);
        }

        if ($stmt->execute()) {
            $result($this->link, $stmt);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function realDestruct()
    {
        $this->prepared->close();
    }
}

