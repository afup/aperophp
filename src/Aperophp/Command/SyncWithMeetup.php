<?php

namespace Aperophp\Command;

use Aperophp\Meetup\EventTransformer;
use Aperophp\Meetup\UserTransformer;
use Aperophp\Repository\City;
use Aperophp\Repository\Drink;
use Aperophp\Repository\DrinkParticipant;
use Aperophp\Repository\User;
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
     * @var User
     */
    private $userRepository;

    /**
     * @var DrinkParticipant
     */
    protected $drinkParticipantRepository;

    /**
     * @param MeetupKeyAuthClient $meetupClient
     * @param Drink $drink
     * @param City $city
     * @param User $user
     * @param DrinkParticipant $drinkParticipant
     * @param array $groupUrlnames
     */
    public function __construct(MeetupKeyAuthClient $meetupClient, Drink $drink, City $city, User $user, DrinkParticipant $drinkParticipant, array $groupUrlnames)
    {
        parent::__construct();
        $this->meetupClient = $meetupClient;
        $this->drinkRepository = $drink;
        $this->cityRepositories = $city;
        $this->userRepository = $user;
        $this->drinkParticipantRepository = $drinkParticipant;
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
        $userTransformer = new UserTransformer();

        foreach ($events as $event) {
            $drink = $eventTransformer->transform($event);

            if (null === $drink) {
                continue;
            }

            if (false === ($foundDrink = $this->drinkRepository->findByMeetupId($drink['meetup_com_id']))) {
                $this->drinkRepository->insert($drink);
            } else {
                $this->drinkRepository->update($drink, array('meetup_com_id' => $drink['meetup_com_id']));
            }
            $drink = $this->drinkRepository->findByMeetupId($drink['meetup_com_id']);

            $command = $this->meetupClient->getCommand('getRsvps', array('event_id' => $drink['meetup_com_id']));
            $command->prepare();
            $rsvps = $command->execute();

            $this->drinkParticipantRepository->delete(array(
                'drink_id' => $drink['id'],
            ));

            foreach ($rsvps as $rsvp) {
                $user =$userTransformer->transform($rsvp);

                if (null === $user) {
                    continue;
                }

                if (false === ($foundUser = $this->userRepository->findByMeetupId($user['meetup_com_id']))) {
                    $this->userRepository->insert($user);
                } else {
                    $this->userRepository->update($user, array('meetup_com_id' => $user['meetup_com_id']));
                }
                $user = $this->userRepository->findByMeetupId($user['meetup_com_id']);

                $drinkParticipant = array(
                    'drink_id' => $drink['id'],
                    'user_id' => $user['id'],
                    'percentage' => 100,
                    'reminder' => 0,
                );

                $this->drinkParticipantRepository->insert($drinkParticipant);
            }
        }
    }
}
