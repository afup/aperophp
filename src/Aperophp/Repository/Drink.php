<?php

namespace Aperophp\Repository;

class Drink extends Repository
{
    const KIND_DRINK        = 'drink';
    const KIND_CONFERENCE   = 'conference';

    public function getTableName()
    {
        return 'Drink';
    }

    /**
     * Find drinks order by day with participants
     *
     * @param integer $limit
     *
     * @return array
     */
    public function findAll($limit = null)
    {
        if (null === $limit) {
            $limit = 3;
        }

        $sql  = sprintf(
            'SELECT d.*, m.username as organizer_username, u.email as organizer_email, c.name as city_name,
                (SELECT COUNT(*) FROM Drink_Participation WHERE drink_id = d.id) as participants_count
            FROM Drink d, Member m, User u, City c
            WHERE d.member_id = m.id
              AND u.member_id = m.id
              AND d.city_id = c.id
            ORDER BY day DESC
            LIMIT %s
        ', $limit);

        return $this->db->fetchAll($sql);
    }

    public function find($id)
    {
        $sql  =
            'SELECT d.*, m.username as organizer_username, u.email as organizer_email, c.name as city_name,
                (SELECT COUNT(*) FROM Drink_Participation WHERE drink_id = d.id) as participants_count
            FROM Drink d, Member m, User u, City c
            WHERE d.member_id = m.id
              AND u.member_id = m.id
              AND d.city_id = c.id
              AND d.id = ?
            LIMIT 1
            ';

        return $this->db->fetchAssoc($sql, array((int) $id));
    }
}
