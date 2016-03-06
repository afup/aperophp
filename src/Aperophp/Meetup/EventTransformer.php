<?php

namespace Aperophp\Meetup;

class EventTransformer
{
    /**
     * @var array
     */
    protected $cities;

    /**
     * @param array $cities
     */
    public function __construct(array $cities)
    {
        $this->cities = $cities;
    }

    /**
     * @param array $event
     *
     * @return array
     *
     * @throws \Exception
     */
    public function transform(array $event)
    {
        $time = $this->transformDate($event['time']);


        if (false === ($cityId = array_search($event['venue']['city'], $this->cities))) {
            throw new \Exception(sprintf("City %s not found", var_export($event['venue']['city'], true)));
        }

        return array(
            'city_id' => $cityId,
            'place' => $event['venue']['address_1'],
            'address' => $event['venue']['address_1'] . ' ' . $event['venue']['city'] . ', ' . $event['venue']['localized_country_name'],
            'latitude' => $event['venue']['lat'],
            'longitude' => $event['venue']['lon'],
            'description' => $event['description'],
            'day' => $time->format('Y-m-d'),
            'hour' => $time->format('H:i'),
            'created_at' => $this->transformDate($event['created'])->format('Y-m-d H:i:s'),
            'updated_at' => $this->transformDate($event['updated'])->format('Y-m-d H:i:s'),
            'meetup_com_id' => $event['id'],
            'meetup_com_event_url' => $event['event_url'],
        );
    }

    /**
     * @param string $date
     *
     * @return \DateTime
     */
    protected function transformDate($date)
    {
        $updated = \DateTime::createFromFormat('U', $date / 1000, new \DateTimeZone('UTC'));
        $updated->setTimezone(new \DateTimeZone('Europe/Paris'));

        return $updated;
    }
}
