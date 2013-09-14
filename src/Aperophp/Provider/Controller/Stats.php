<?php

namespace Aperophp\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Aperophp\Repository\City;
use Aperophp\Lib\Stats as StatsLib;

class Stats implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->match('stats_{type}-{city}.html', function(Request $request, $type, $city) use ($app)
        {
          $app['session']->set('menu', 'stats');

          $types = StatsLib::getTypes();

          if (!isset($types[$type])) {
            $type = 'all';
          }

          $dateFrom = StatsLib::getDateFrom($type);


          $stats = new StatsLib($app['db'], $dateFrom, $city);
          $totalCount = $stats->getCount();


          $geo = array();
          foreach ($stats->getGeoInformations() as $info) {
            $geo[] = array($info['latitude'], $info['longitude'], $info['description']);
          }

          $displayedDate = $dateFrom;
          if (count($first = $stats->findFirst())) {
            $displayedDate = $first['day'];
          }

          $cities = array(City::ALL => 'Toutes') + $app['cities']->findRecurrentInAssociativeArray();

          $various = array(
            'month' => $stats->getMostUsedMonth(),
            'average' => $stats->getAverageParticipants(),
            'max' => $stats->getMaxParticipants(),
          );

          return $app['twig']->render('stats/stats.html.twig', array(
            'total' => $totalCount,
            'total_participants' => $stats->countAllParticipants(),
            'avg_participants' => $stats->averageParticipantsByCity('all' == $type),
            'date_participants' => $stats->countParticipantsByDate(),
            'date_from' => $displayedDate,
            'geo' => $geo,
            'type' => $type,
            'types' => $types,
            'city' => $city,
            'cities' => $cities,
            'display_all_cities' => $city == City::ALL,
            'various' => $various
            ));
        })
        ->bind('_stats')
        ->method('GET');

        return $controllers;
    }
}
