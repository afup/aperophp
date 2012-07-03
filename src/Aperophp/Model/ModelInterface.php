<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 * Generic model interface
 *
 * @author Mikael Randy <mikael.randy@gmail.com>
 */
abstract class ModelInterface
{
    /**
     * Doctrine connection
     * @type Connection
     */
    protected $connection;

    /**
     * Constructor
     *
     * @author      Mikael Randy <mikael.randy@gmail.com>
     * @since       21 janv. 2012 - Mikael Randy <mikael.randy@gmail.com>
     * @version 1.0 - 21 janv. 2012 - Mikael Randy <mikael.randy@gmail.com>
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}
