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

use Phossa\Db\Driver\DriverAwareTrait;

/**
 * Profiler
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ProfilerInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Profiler implements ProfilerInterface
{
    use DriverAwareTrait;

    /**
     * Current executed SQL
     *
     * @var    string
     * @access protected
     */
    protected $sql = '';

    /**
     * Parameters cache
     *
     * @var    array
     * @access protected
     */
    protected $params;

    /**
     * Execution time
     *
     * @var    float
     * @access protected
     */
    protected $execution_time = 0.0;

    /**
     * {@inheritDoc}
     */
    public function setSql(/*# string */ $sql)
    {
        // init
        $this->sql = $sql;
        $this->params = [];
        $this->execution_time = 0.0;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setParameters(array $parameters)
    {
        $this->params = $parameters;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSql()/*# : string */
    {
        if (empty($this->params)) {
            return $this->sql;
        } else {
            $count  = 0;
            $params = $this->params;
            return preg_replace_callback(
                '/\?|\:\w+/',
                function($m) use ($count, $params) {
                    if ('?' === $m[0]) {
                        $res = $params[$count++];
                    } else {
                        $res = isset($params[$m[0]]) ? $params[$m[0]] :
                        $params[substr($m[0],1)];
                    }
                    return $this->getDriver()->quote($res);
                },
                $this->sql
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setExecutionTime(/*# float */ $time)
    {
        $this->execution_time = (float) $time;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExecutionTime()/*# : float */
    {
        return $this->execution_time;
    }
}
