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

namespace Phossa\Db\Driver;

/**
 * ErrorTrait
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ErrorInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
trait ErrorTrait
{
    use DriverAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function isSuccessful()/*# : bool */
    {
        return 0 === $this->getErrorCode();
    }

    /**
     * {@inheritDoc}
     */
    public function getErrorCode()/*# : int */
    {
        return $this->getDriver()->getErrorCode();
    }

    /**
     * {@inheritDoc}
     */
    public function getError()/*# : string */
    {
        return $this->getDriver()->getError();
    }
}
