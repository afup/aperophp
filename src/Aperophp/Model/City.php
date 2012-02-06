<?php 

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 * City model.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 6 févr. 2012 
 * @version 1.0 - 6 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class City extends ModelInterface
{
    /**
     * Get all.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 6 févr. 2012 
     * @version 1.0 - 6 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param Connection $connection
     */
    static public function findAll(Connection $connection)
    {
        $sql = "SELECT id, name FROM City ORDER BY name";
        $aData = $connection->fetchAll($sql);
        
        $result = array();
        foreach ($aData as $data)
        {
            $result[$data['id']] = $data['name'];
        }
        
        return $result;
    }
}