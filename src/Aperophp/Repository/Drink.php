<?php

namespace Aperophp\Repository;

/**
 * Drink repository
 */
class Drink extends Repository
{
    const KIND_DRINK        = 'drink';
    const KIND_CONFERENCE   = 'talk';

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
                (%s) as participants_count
            FROM Drink d
            LEFT JOIN Member m ON (d.member_id = m.id)
            LEFT JOIN User u ON (u.member_id = m.id)
            JOIN City c ON (d.city_id = c.id)
            ORDER BY day DESC
            LIMIT %d
        ', self::getCountParticipantsQuery(), $limit);

        return $this->db->fetchAll($sql);
    }


    /**
     * Find futur drinks order by day, with participants
     */
    public function findNext($limit = null)
    {
        if (null === $limit) {
            $limit = 3;
        }

        $today = new \DateTime();

        $sql  = sprintf(
            'SELECT d.*, m.username as organizer_username, u.email as organizer_email, c.name as city_name,
                (%s) as participants_count
            FROM Drink d
            JOIN City c ON (d.city_id = c.id)
            LEFT JOIN Member m ON (d.member_id = m.id)
            LEFT JOIN User u ON (m.id = u.member_id)
            WHERE d.day >= "%s"
              ORDER BY day ASC
            LIMIT %s
        ',
        self::getCountParticipantsQuery(),
        $today->format('Y-m-d') ,
        $limit);

        return $this->db->fetchAll($sql);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function find($id)
    {
        return $this->findByAttr('id', (int) $id);
    }

    /**
     * @param int $meetupId
     *
     * @return array
     */
    public function findByMeetupId($meetupId)
    {
        return $this->findByAttr('meetup_com_id', $meetupId);
    }

    /**
     * Load a specific drink
     *
     * @param string $attr
     * @param int $value
     *
     * @return array
     */
    protected function findByAttr($attr, $value)
    {
        $sql  =
            sprintf('SELECT d.*, m.username as organizer_username, u.email as organizer_email, c.name as city_name,
                (%s) as participants_count, m.id as member_id
            FROM Drink d
            LEFT JOIN Member m ON (d.member_id = m.id)
            LEFT JOIN User u ON (u.member_id = m.id)
            JOIN City c ON (d.city_id = c.id)
            WHERE d.%s = ?
            LIMIT 1
            ', self::getCountParticipantsQuery(), $attr);

        return $this->db->fetchAssoc($sql, array($value));
    }

    public function findAllKindsInAssociativeArray()
    {
        return array(
            self::KIND_DRINK      => self::KIND_DRINK,
            self::KIND_CONFERENCE => self::KIND_CONFERENCE,
        );
    }

    public static function getCountParticipantsQuery()
    {
      return "SELECT COUNT(*) FROM Drink_Participation WHERE drink_id = d.id AND percentage > 0";
    }

}
