<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 * Drink comment model.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 18 févr. 2012
 * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class DrinkComment extends ModelInterface
{
    protected $id;
    protected $createdAt;
    protected $content;
    protected $drinkId;
    protected $userId;
    protected $user;

    /**
     * Find all order by day.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 7 févr. 2012
     * @version 1.2 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param Connection $connection
     */
    static public function findByDrinkId(Connection $connection, $drinkId)
    {
        $sql = 'SELECT * FROM Drink_Comment WHERE drink_id = ? ORDER BY created_at';
        $aData = $connection->fetchAll($sql, array($drinkId));

        $aDrinkComment = array();
        foreach ($aData as $data) {
            $oDrinkComment = new self($connection);
            $oDrinkComment
                ->setId($data['id'])
                ->setCreatedAt($data['created_at'])
                ->setContent($data['content'])
                ->setDrinkId($data['drink_id'])
                ->setUserId($data['user_id']);

            $aDrinkComment[$data['id']] = $oDrinkComment;
        }

        return $aDrinkComment;
    }

    /**
     * Save.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 18 févr. 2012
     * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    public function save()
    {
        return $this->isNew() ? $this->insert() : $this->update();
    }

    /**
     * Is new ?
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 18 févr. 2012
     * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @return boolean
     */
    public function isNew()
    {
        return $this->id ? false : true;
    }

    /**
     * Insert.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 18 févr. 2012
     * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    protected function insert()
    {
        $stmt = $this->connection->insert('Drink_Comment', array(
            'created_at' => $this->createdAt,
            'content'    => $this->content,
            'drink_id'   => $this->drinkId,
            'user_id'    => $this->userId,
        ));

        $this->id = $this->connection->lastInsertId();

        return 1 === $stmt;
    }

    /**
     * Update.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 18 févr. 2012
     * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    protected function update()
    {
        return 1 === $this->connection->update('Drink_Comment', array(
            'created_at' => $this->createdAt,
            'content'    => $this->content,
            'drink_id'   => $this->drinkId,
            'user_id'    => $this->userId,
        ), array(
            'id' => $this->id
        ));
    }

    /**
     * Return user associated.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 18 févr. 2012
     * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @return \Aperophp\Model\User
     */
    public function getUser()
    {
        if (!$this->user) {
            $this->user = User::findOneById($this->connection, $this->userId);
        }

        return $this->user;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getDrinkId()
    {
        return $this->drinkId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function setDrinkId($drinkId)
    {
        $this->drinkId = $drinkId;

        return $this;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }
}
