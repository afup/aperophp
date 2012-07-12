<?php

namespace Aperophp\Lib;

/**
 * Aperophp utils.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 4 févr. 2012
 * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class Utils
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Hash my string.
     *
     * @param string $str  The string to hash
     * @param string $salt The salt to use (if null, use app['secret'])
     *
     * @return string
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 4 févr. 2012
     * @version 1.1 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    public function hash($str, $salt = null)
    {
        $salt = $salt ? $salt : $this->app['secret'];

        return sha1($str.$salt);
    }
}
