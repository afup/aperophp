<?php

namespace Aperophp\Repository;

class DrinkParticipant extends Repository
{

    public function getTableName()
    {
        return 'Drink_Participation';
    }

    public function findOne($drinkId, $userId)
    {
        $sql = 'SELECT * FROM Drink_Participation WHERE drink_id = ? AND user_id = ? LIMIT 1';

        return $this->db->fetchAssoc($sql, array((int) $drinkId, (int) $userId));
    }

    public function findByDrinkId($drinkId)
    {
        $sql = '
            SELECT u.*, p.percentage as percentage,
            (SELECT username FROM Member m WHERE m.id = u.member_id) as username
            FROM User u, Drink_Participation p
            WHERE p.user_id = u.id
              AND p.drink_id = ?
        ';

        return $this->db->fetchAll($sql, array((int) $drinkId));
    }

    public function findDrinksByUserId($userId)
    {
        $sql = 'SELECT d.* FROM Drink d, Drink_Participation p WHERE p.drink_id = d.id AND user_id = ?';

        return $this->db->fetchAll($sql, array((int) $userId));
    }

    public function findAllPresencesInAssociativeArray()
    {
        return array(
            100 => 'For sure, I will be there',
            70  => 'I will probably be there',
            30  => 'I will try to be there',
            0   => 'I won\'t be there',
        );
    }
}
