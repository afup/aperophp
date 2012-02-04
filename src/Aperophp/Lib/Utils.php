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
    /**
     * Hash my string.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 4 févr. 2012 
     * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param string $str
     * @param string $salt
     * @return string
     */
    static public function hashMe($str, $salt)
    {
        return sha1($str.$salt);
    }
}