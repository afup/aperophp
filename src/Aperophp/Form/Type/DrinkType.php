<?php

namespace Aperophp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Doctrine\DBAL\Connection;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Aperophp\Repository;
use Aperophp\Form\EventListener\DataFilterSubscriber;

/**
 * Drink form.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 6 févr. 2012
 * @version 1.1 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class DrinkType extends AbstractType
{
    protected $cityRepository;
    protected $cities = null;

    public function __construct(Repository\City $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new DataFilterSubscriber($builder));

        $builder
            ->add('place', 'hidden')
            ->add('address', 'hidden')
            ->add('latitude', 'hidden')
            ->add('longitude', 'hidden')
            ->add('day', 'hidden')
            ->add('hour', 'choice', array(
                'label' => 'Heure',
                'choices' => $options['hours']
            ))
            ->add('city_id', 'choice', array(
                'label' => 'Ville',
                'choices' => $options['cities']
            ))
            ->add('description', 'textarea', array(
                'label' => 'Description'
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // Collection Constraint
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => array(
                'place'       => array(
                    new Constraints\NotNull(),
                    new Constraints\MaxLength(array('limit' => 100)),
                ),
                'address'     => new Constraints\MaxLength(array('limit' => 100)),
                'latitude'    => new Constraints\Min(array('limit' => 0)),
                'longitude'   => new Constraints\Min(array('limit' => 0)),
                'day'         => array(
                    new Constraints\NotNull(),
                    new Constraints\Date(),
                ),
                'hour'        => array(
                    new Constraints\NotNull(),
                    new Constraints\Time(),
                ),
                'city_id'     => array(
                    new Constraints\NotNull(),
                    new Constraints\Choice(array(
                        'choices' => array_keys($this->getCities())
                    )),
                ),
                'description' => new Constraints\NotNull(),
            ),
            'allowExtraFields' => false,
        ));

        // Hours
        $hours = array();
        $oStartDate = new \DateTime('2000-01-01');
        $oEndDate = new \DateTime('2000-01-02');

        do {
            $hours[$oStartDate->format('H:i:s')] = $oStartDate->format('H\hi');
            $oStartDate->add(new \DateInterval('PT30M'));
        } while ($oStartDate < $oEndDate);

        $resolver->setDefaults(array(
            'validation_constraint' => $collectionConstraint,
            'hours'                 => $hours,
            'cities'                => $this->getCities(),
        ));
    }

    public function getName()
    {
        return 'drink';
    }

    protected function getCities()
    {
        if (null !== $this->cities) {
            return $this->cities;
        }

        return $this->cityRepository->findAllInAssociativeArray();
    }
}
