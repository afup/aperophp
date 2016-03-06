<?php

namespace tests\units\Aperophp\Meetup;

require_once __DIR__.'/../../../../vendor/autoload.php';

class EventTransformer extends \atoum
{
    public function testHash()
    {
        $event = array (
            'utc_offset' => 3600000,
            'venue' =>
                array (
                    'country' => 'fr',
                    'localized_country_name' => 'France',
                    'city' => 'Lyon',
                    'address_1' => '26 rue Louis Guérin',
                    'address_2' => '1er étage',
                    'name' => 'Amabla - Elao',
                    'lon' => 4.8601939999999999,
                    'id' => 23511318,
                    'lat' => 45.777003999999998,
                    'repinned' => false,
                ),
            'headcount' => 50,
            'visibility' => 'public',
            'waitlist_count' => 0,
            'created' => 1434297459000,
            'rating' =>
                array (
                    'count' => 4,
                    'average' => 4,
                ),
            'maybe_rsvp_count' => 0,
            'description' => '<p>Bonjour à tous,</p> <p>Nous avons le plaisir de vous annoncer l\'organisation du premier Meetup Ansible Lyonnais !</p> <p>En fonction du nombre de personnes présentes l\'évènement aura lieu soit chez ELAO soit chez Amabla si nous sommes nombreux.</p> <p><b>Au programme:</b></p> <p>• 19h30 Émilien MANTEL : "Ansible et AWS, approche de l\'autoscale"</p> <p>•  20h10 : Guewen FAIVRE (ELAO) : "The automation journey of a web agency"</p> <p>A tous les speakers intéressés n\'hésitez pas à m\'envoyer vos propositions de talks pour les futurs meetups ! </p> <p><br/>Pizza et boissons vous attendent !</p> <p>Sponsors : ELAO &amp; Ansible </p> <p>--- </p> <p>Hi everyone ! </p> <p>We are pleased to announce the date of the first Ansible Lyon Meetup. According to how many "Ansi-bulls" we\'ll be, the event will be hosted by ELAO or Amabla.</p> <p>

• 7h30 PM Émilien MANTEL : "Ansible and AWS, approach to auto-scaling"</p> <p>• 8h10 PM : Guewen FAIVRE (ELAO) : "The automation journey of a web agency"</p> <p>To every speakers, you can send me your suggestions for Talks, more meetups are comming !<br/>Pizzas and drinks awaiting you :) Sponsors : ELAO &amp; Ansible</p>',
            'event_url' => 'http://www.meetup.com/Ansible-Lyon/events/223238801/',
            'yes_rsvp_count' => 48,
            'name' => 'Ansible Lyon kickoff meeting',
            'id' => '223238801',
            'time' => 1450375200000,
            'updated' => 1450459737000,
            'group' =>
                array (
                    'join_mode' => 'open',
                    'created' => 1434297356000,
                    'name' => 'Ansible Lyon',
                    'group_lon' => 4.8299999237060547,
                    'id' => 18672205,
                    'urlname' => 'Ansible-Lyon',
                    'group_lat' => 45.759998321533203,
                    'who' => 'Ansi-gônes',
                ),
            'status' => 'past',
        );

        $expectedDrinkDescription = <<<EOF
<p>Bonjour à tous,</p> <p>Nous avons le plaisir de vous annoncer l'organisation du premier Meetup Ansible Lyonnais !</p> <p>En fonction du nombre de personnes présentes l'évènement aura lieu soit chez ELAO soit chez Amabla si nous sommes nombreux.</p> <p><b>Au programme:</b></p> <p>• 19h30 Émilien MANTEL : "Ansible et AWS, approche de l'autoscale"</p> <p>•  20h10 : Guewen FAIVRE (ELAO) : "The automation journey of a web agency"</p> <p>A tous les speakers intéressés n'hésitez pas à m'envoyer vos propositions de talks pour les futurs meetups ! </p> <p><br/>Pizza et boissons vous attendent !</p> <p>Sponsors : ELAO &amp; Ansible </p> <p>--- </p> <p>Hi everyone ! </p> <p>We are pleased to announce the date of the first Ansible Lyon Meetup. According to how many "Ansi-bulls" we'll be, the event will be hosted by ELAO or Amabla.</p> <p>

• 7h30 PM Émilien MANTEL : "Ansible and AWS, approach to auto-scaling"</p> <p>• 8h10 PM : Guewen FAIVRE (ELAO) : "The automation journey of a web agency"</p> <p>To every speakers, you can send me your suggestions for Talks, more meetups are comming !<br/>Pizzas and drinks awaiting you :) Sponsors : ELAO &amp; Ansible</p>
EOF;


        $cities = array(
            125 => 'Luxembourg',
            126 => 'Lyon',
            127 => 'Marcq-En-Baroeul',
        );

        $this
            ->if($transformer = new \Aperophp\Meetup\EventTransformer($cities))
            ->then
                ->array($transformer->transform($event))
                    ->integer['city_id']->isEqualTo(126)
                    ->string['place']->isEqualTo('26 rue Louis Guérin')
                    ->string['address']->isEqualTo('26 rue Louis Guérin Lyon, France')
                    ->float['latitude']->isEqualTo('45.777004')
                    ->float['longitude']->isEqualTo('4.860194')
                    ->string['description']->isEqualTo($expectedDrinkDescription)
                    ->string['day']->isEqualTo('2015-12-17')
                    ->string['hour']->isEqualTo('19:00')
                    ->string['created_at']->isEqualTo('2015-06-14 17:57:39')
                    ->string['updated_at']->isEqualTo('2015-12-18 18:28:57')
                    ->string['meetup_com_id']->isEqualTo('223238801')
        ;
    }
}
