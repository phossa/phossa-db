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

namespace Phossa\Db\Message;

use Phossa\Shared\Message\MessageAbstract;

/**
 * Message class for Phossa\Db
 *
 * @package \Phossa\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     MessageAbstract
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Message extends MessageAbstract
{
    /**#@+
     * @var   int
     */

    /**
     * DB driver not found
     */
    const DB_DRIVER_NOTFOUND        = 1603251436;

    /**
     * Invalid DB connect link, %s
     */
    const DB_INVALID_LINK           = 1603251437;

    /**
     * DB connect failed "%s" "%s"
     */
    const DB_CONNECT_FAIL           = 1603251438;

    /**
     * Invalid driver type "%s"
     */
    const DB_INVALID_DRIVER         = 1603251439;

    /**
     * Rollback called before beginning
     */
    const DB_INVALID_ROLLBACK       = 1603251440;

    /**
     * Extension for "%s" not loaded
     */
    const DB_EXTENSION_NOT_LOAD     = 1603251441;

    /**
     * "%s" not a selective query
     */
    const DB_SQL_NOT_QUERY          = 1603251442;

    /**
     * No statement prepared yet
     */
    const DB_SQL_NOT_PREPARED       = 1603251443;

    /**
     * No driver found
     */
    const DB_SQL_NO_DRIVER          = 1603251444;

    /**
     * Missing connect parameters
     */
    const DB_CONNECT_MISSING        = 1603251445;

    /**
     * Invalid db result
     */
    const DB_INVALID_RESULT         = 1603251446;

    /**
     * Can not prepare another statement
     */
    const DB_SQL_PREPARED_TWICE     = 1603251447;

    /**#@-*/

    /**
     * {@inheritdoc}
     */
    protected static $messages = [
        self::DB_DRIVER_NOTFOUND    => 'DB driver not found',
        self::DB_INVALID_LINK       => 'Invalid DB connect link, %s',
        self::DB_CONNECT_FAIL       => 'DB connect failed "%s" "%s"',
        self::DB_INVALID_DRIVER     => 'Invalid driver type "%s"',
        self::DB_INVALID_ROLLBACK   => 'Rollback called before beginning',
        self::DB_EXTENSION_NOT_LOAD => 'Extension for "%s" not loaded',
        self::DB_SQL_NOT_QUERY      => '"%s" not a selective query',
        self::DB_SQL_NOT_PREPARED   => 'No statement prepared yet',
        self::DB_SQL_NO_DRIVER      => 'No driver found',
        self::DB_CONNECT_MISSING    => 'Missing connect parameters',
        self::DB_INVALID_RESULT     => 'Invalid db result',
        self::DB_SQL_PREPARED_TWICE => 'Can not prepare another statement',
    ];
}
