<?php

namespace Aperophp\Repository;

class DrinkComment extends Repository
{
    public function getTableName()
    {
        return 'Drink_Comment';
    }

    public function findByDrinkId($drinkId)
    {
        $sql = 'SELECT c.*, u.email as user_email FROM Drink_Comment c, User u WHERE c.user_id = u.id AND drink_id = ? ORDER BY created_at';

        return $this->db->fetchAll($sql, array((int) $drinkId));
    }

    public function findOne($drinkId, $userId)
    {
        $sql = 'SELECT * FROM Drink_Comment WHERE drink_id = ? AND user_id = ? LIMIT 1';

        return $this->db->fetchAssoc($sql, array((int) $drinkId, (int) $userId));
    }
}
