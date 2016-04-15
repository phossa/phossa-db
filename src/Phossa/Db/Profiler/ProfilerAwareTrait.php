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

use Phossa\Db\Message\Message;
use Phossa\Db\Exception\LogicException;

/**
 * ProfilerAwareTrait
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ProfilerAwareInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
trait ProfilerAwareTrait
{
    /**
     * the profiler
     *
     * @var    ProfilerInterface
     * @access protected
     */
    protected $profiler;

    /**
     * {@inheritDoc}
     */
    public function isProfiling()/*# : bool */
    {
        return null !== $this->profiler;
    }

    /**
     * {@inheritDoc}
     */
    public function setProfiler(ProfilerInterface $profiler = null)
    {
        if ($profiler) {
            $this->profiler = $profiler;
        } else {
            $this->profiler = new Profiler();
        }
        $this->profiler->setDriver($this);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getProfiler()/*# : ProfilerInterface */
    {
        if ($this->isProfiling()) {
            return $this->profiler;
        } else {
            throw new LogicException(
                Message::get(Message::DB_UNKNOWN_PROFILER),
                Message::DB_UNKNOWN_PROFILER
            );
        }
    }
}
