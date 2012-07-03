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
    protected $id;
    protected $name;

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
        $sql = 'SELECT id, name FROM City ORDER BY name';
        $aData = $connection->fetchAll($sql);

        $result = array();
        foreach ($aData as $data) {
            $result[$data['id']] = $data['name'];
        }

        return $result;
    }

    /**
     * FInd one by id.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 7 févr. 2012
     * @version 1.0 - 7 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param Connection $connection
     * @param integer $id
     */
    static public function findOneById(Connection $connection, $id)
    {
        $data = $connection->fetchAssoc('SELECT * FROM City WHERE id = ?', array($id));

        if (!$data) {
            return null;
        }

        $oCity = new self($connection);

        $oCity
            ->setId($data['id'])
            ->setName($data['name']);

        return $oCity;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
