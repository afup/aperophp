<?php 

namespace Aperophp\Model;

class City extends ModelInterface
{
    public function getAll()
    {
        $sql = "SELECT * FROM City ORDER BY name";

        return $this->connection->fetchAll($sql);
    }
}