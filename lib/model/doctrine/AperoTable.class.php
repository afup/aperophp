<?php

class AperoTable extends Doctrine_Table
{
  public static function getInstance()
  {
    return Doctrine_Core::getTable('Apero');
  }

  public function getActiveAperos(Doctrine_Query $q = null)
  {
    if (is_null($q))
    {
      $q = Doctrine_Query::create();
      $q->from('Apero a');
    }

    $q->where('a.is_active = ?', true);
    $q->addOrderBy('a.date_at DESC');

    return $q->execute();
  }

  public function getComingAperos($max = 10)
  {
    $q = Doctrine_Query::create();
    $q->from('Apero a');
    $q->where('a.is_active = ?', true);
    $q->andWhere('CONCAT(a.date_at, a.time_at) >= ?', date('Y-m-d H:i', time()));
    $q->addOrderBy('a.date_at ASC');
    $q->limit($max);

    return $q->execute();
  }

  public function getPassedAperos($max = 10)
  {
    $q = Doctrine_Query::create();
    $q->from('Apero a');
    $q->where('a.is_active = ?', true);
    $q->andWhere('CONCAT(a.date_at, a.time_at) < ?', date('Y-m-d H:i', time()));
    $q->addOrderBy('a.date_at DESC');
    $q->limit($max);

    return $q->execute();
  }

  public function getComingAperosForUser(sfGuardUser $user)
  {
    $q = $this->createQuery('a')
      ->leftJoin('a.AperoUser au')
      ->where('CONCAT(a.date_at, a.time_at) >= ?', date('Y-m-d H:i', time()))
      ->andWhere('a.is_active = ?', true)
      ->andWhere('au.user_id = ?', $user->getId())
      ->addOrderBy('a.date_at ASC');

    return $q->execute();
  }

  public function getPassedAperosForUser(sfGuardUser $user)
  {
    $q = $this->createQuery('a')
      ->leftJoin('a.AperoUser au')
      ->where('CONCAT(a.date_at, a.time_at) < ?', date('Y-m-d H:i', time()))
      ->andWhere('a.is_active = ?', true)
      ->andWhere('au.user_id = ?', $user->getId())
      ->addOrderBy('a.date_at DESC');

    return $q->execute();
  }
}