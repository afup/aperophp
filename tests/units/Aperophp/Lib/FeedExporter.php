<?php

namespace tests\units\Aperophp\Lib;

use Aperophp\Lib\FeedExporter as TestedClass;

class FeedExporter extends \atoum
{
    public function testExport()
    {
        $this
            ->given($exporter = new TestedClass)
            ->and(
                $drink = array (
                    'id' => '308',
                    'place' => '11 Rue Terme',
                    'address' => '11 Rue Terme, 69001 Lyon, France',
                    'day' => '2013-10-29',
                    'hour' => '19:00:00',
                    'kind' => 'drink',
                    'description' => 'ApÃ©ro PHP Lyonnais du mois d\'octobre.

Plus d\'informations sur le blog de l\'AFUP Lyon : http://lyon.afup.org/2013/10/09/apero-php-mardi-29-octobre-a-19h/',
                    'map' => NULL,
                    'member_id' => '1096',
                    'city_id' => '126',
                    'latitude' => '45.769190',
                    'longitude' => '4.831590',
                    'meetup_com_id' => NULL,
                    'meetup_com_event_url' => NULL,
                    'created_at' => '2013-10-09 11:07:03',
                    'updated_at' => '2013-10-09 11:22:30',
                    'organizer_username' => 'agallou',
                    'organizer_email' => 'user@gmail.com',
                    'city_name' => 'Lyon',
                    'participants_count' => '19',
                )
            )
            ->then
                ->xml($export = $exporter->export([$drink], new \DateTime("2016-12-25T13:42:23+01:00")))
                    ->isValidAgainstSchema->schema(__DIR__ . '/../../../resources/atom.xsd.xml')
                ->string($export)
                    ->isEqualTo(<<<EOF
<?xml version="1.0"?>
<feed xmlns="http://www.w3.org/2005/Atom"><title>AperoPHP</title><subtitle>Liste des ap&#xE9;ro PHP &#xE0; venir</subtitle><link href="http://aperophp.net"/><updated>2016-12-25T13:42:23+01:00</updated><author><name>AFUP</name><email>contact@afup.org</email></author><id>http://aperophp.net/</id><entry><title>Lyon le 29/10/2013 &#xE0; 00:00</title><id>http://aperophp.net/308/view.html</id><link href="http://aperophp.net/308/view.html"/><updated>2013-10-09T11:22:30+02:00</updated><summary type="html">Ap&#xC3;&#xA9;ro PHP Lyonnais du mois d'octobre.

Plus d'informations sur le blog de l'AFUP Lyon : http://lyon.afup.org/2013/10/09/apero-php-mardi-29-octobre-a-19h/</summary></entry></feed>

EOF
                    )
        ;
    }
}
