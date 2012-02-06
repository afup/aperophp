<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 *	Generic model interface
 *
 * 	@author Mikael Randy <mikael.randy@gmail.com>
 */
class Drink extends ModelInterface
{
    const KIND_DRINK        = 'drink';
    const KIND_CONFERENCE   = 'conference';

    public function getKinds()
    {
        return array(
            self::KIND_DRINK        => 'Apéro',
            self::KIND_CONFERENCE   => 'Conférence',
        );
    }
}