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

use Phossa\Db\Exception\LogicException;

/**
 * ProfilerAwareInterface
 *
 * @package Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface ProfilerAwareInterface
{
    /**
     * Is profiling enabled
     *
     * @return bool
     * @access public
     */
    public function isProfiling()/*# : bool */;

    /**
     * Set the profile or using the default one
     *
     * @param  ProfilerInterface $profiler
     * @return this
     * @access public
     */
    public function setProfiler(ProfilerInterface $profiler = null);

    /**
     * Get the profiler
     *
     * @return ProfilerInterface
     * @throws LogicException if no profiler set
     * @access public
     */
    public function getProfiler()/*# : ProfilerInterface */;
}
