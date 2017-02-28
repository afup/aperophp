<?php

namespace tests\units\Aperophp\Meetup;

class UserTransformer extends \atoum
{
    public function testHash()
    {
        $rsvp = array (
            'venue' =>
                array (
                    'country' => 'fr',
                    'localized_country_name' => 'France',
                    'city' => 'Villleurbanne',
                    'address_1' => '26 rue Louis Guérin',
                    'address_2' => '1er étage',
                    'name' => 'Amabla - Elao',
                    'lon' => 4.8601939999999999,
                    'id' => 23511318,
                    'lat' => 45.777003999999998,
                    'repinned' => false,
                ),
            'created' => 1449093417000,
            'response' => 'yes',
            'member_photo' =>
                array (
                    'highres_link' => 'http://photos1.meetupstatic.com/photos/member/d/5/e/0/highres_208014752.jpeg',
                    'photo_id' => 208014752,
                    'photo_link' => 'http://photos1.meetupstatic.com/photos/member/d/5/e/0/member_208014752.jpeg',
                    'thumb_link' => 'http://photos1.meetupstatic.com/photos/member/d/5/e/0/thumb_208014752.jpeg',
                ),
            'tallies' =>
                array (
                    'no' => 11,
                    'waitlist' => 0,
                    'maybe' => 0,
                    'yes' => 48,
                ),
            'guests' => 0,
            'member' =>
                array (
                    'member_id' => 99837872,
                    'name' => 'Adrien Gallou',
                ),
            'rsvp_id' => 1583101090,
            'mtime' => 1449093417000,
            'event' =>
                array (
                    'name' => 'Ansible Lyon kickoff meeting',
                    'id' => '223238801',
                    'time' => 1450375200000,
                    'event_url' => 'http://www.meetup.com/Ansible-Lyon/events/223238801/',
                ),
            'group' =>
                array (
                    'join_mode' => 'open',
                    'created' => 1457288152065,
                    'group_lon' => 4.8299999237060547,
                    'id' => 18672205,
                    'urlname' => 'Ansible-Lyon',
                    'group_lat' => 45.759998321533203,
                ),
            );

        $expectedDrinkDescription = <<<EOF
<p>Bonjour à tous,</p> <p>Nous avons le plaisir de vous annoncer l'organisation du premier Meetup Ansible Lyonnais !</p> <p>En fonction du nombre de personnes présentes l'évènement aura lieu soit chez ELAO soit chez Amabla si nous sommes nombreux.</p> <p><b>Au programme:</b></p> <p>• 19h30 Émilien MANTEL : "Ansible et AWS, approche de l'autoscale"</p> <p>•  20h10 : Guewen FAIVRE (ELAO) : "The automation journey of a web agency"</p> <p>A tous les speakers intéressés n'hésitez pas à m'envoyer vos propositions de talks pour les futurs meetups ! </p> <p><br/>Pizza et boissons vous attendent !</p> <p>Sponsors : ELAO &amp; Ansible </p> <p>--- </p> <p>Hi everyone ! </p> <p>We are pleased to announce the date of the first Ansible Lyon Meetup. According to how many "Ansi-bulls" we'll be, the event will be hosted by ELAO or Amabla.</p> <p>

• 7h30 PM Émilien MANTEL : "Ansible and AWS, approach to auto-scaling"</p> <p>• 8h10 PM : Guewen FAIVRE (ELAO) : "The automation journey of a web agency"</p> <p>To every speakers, you can send me your suggestions for Talks, more meetups are comming !<br/>Pizzas and drinks awaiting you :) Sponsors : ELAO &amp; Ansible</p>
EOF;

        $this
            ->if($transformer = new \Aperophp\Meetup\UserTransformer())
            ->then
                ->array($transformer->transform($rsvp))
                    ->integer['meetup_com_id']->isEqualTo(99837872)
                    ->string['firstname']->isEqualTo("Adrien Gallou")
        ;

        $rsvpNo = $rsvp;
        $rsvp['response'] = 'no';

        $this
            ->if($transformer = new \Aperophp\Meetup\UserTransformer())
            ->then
                ->variable($transformer->transform($rsvp))->isNull();
        ;
    }
}
