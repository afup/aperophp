<?php

namespace Aperophp\Command;

use Aperophp\Meetup\EventTransformer;
use Aperophp\Repository\City;
use Aperophp\Repository\Drink;
use DMS\Service\Meetup\MeetupKeyAuthClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncWithMeetup extends Command
{
    /**
     * @var MeetupKeyAuthClient
     */
    protected $meetupClient;

    /**
     * @var Drink
     */
    protected $drinkRepository;

    /**
     * @var City
     */
    protected $cityRepositories;

    /**
     * @var array
     */
    protected $groupUrlnames;

    /**
     * @param MeetupKeyAuthClient $meetupClient
     * @param Drink $drink
     * @param City $city
     * @param array $groupUrlnames
     */
    public function __construct(MeetupKeyAuthClient $meetupClient, Drink $drink, City $city, array $groupUrlnames)
    {
        parent::__construct();
        $this->meetupClient = $meetupClient;
        $this->drinkRepository = $drink;
        $this->cityRepositories = $city;
        $this->groupUrlnames = $groupUrlnames;
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('sync-with-meetup')
            ->setDescription('Sync drinks with meetup.com')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->meetupClient->getCommand(
            'GetEvents',
            array('group_urlname' => implode(',', $this->groupUrlnames), 'status' => 'past')
        );

        $command->prepare();
        $events = $command->execute();

        $eventTransformer = new EventTransformer($this->cityRepositories->findAllInAssociativeArray());

        foreach ($events as $event) {
            $drink = $eventTransformer->transform($event);

            if (false === ($foundDrink = $this->drinkRepository->findByMeetupId($drink['meetup_com_id']))) {
                $this->drinkRepository->insert($drink);
            } else {
                $this->drinkRepository->update($drink, array('meetup_com_id' => $drink['meetup_com_id']));
            }
        }
    }
}
