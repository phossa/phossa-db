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

namespace Phossa\Db\Profiler;

use Phossa\Db\Driver\DriverAwareInterface;

/**
 * ProfilerInterface
 *
 * @package \Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface ProfilerInterface extends DriverAwareInterface
{
    /**
     * Set the executing SQL
     *
     * @param  string
     * @return $this
     * @access public
     */
    public function setSql(/*# string */ $sql);

    /**
     * Set the parameters
     *
     * @param  array
     * @return $this
     * @access public
     */
    public function setParameters(array $parameters);

    /**
     * Get the executed SQL
     *
     * @return string
     * @access public
     */
    public function getSql()/*# : string */;

    /**
     * Set execution time
     *
     * @param  float $time
     * @return $this
     * @access public
     */
    public function setExecutionTime(/*# float */ $time);

    /**
     * Get execution time
     *
     * @return float $time
     * @access public
     */
    public function getExecutionTime()/*# : float */;
}
