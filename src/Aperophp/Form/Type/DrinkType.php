<?php

namespace Aperophp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Aperophp\Validator\Constraints as AperophpConstraints;
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
    protected $drinkRepository;
    protected $kinds = null;

    public function __construct(Repository\City $cityRepository, Repository\Drink $drinkRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->drinkRepository = $drinkRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new DataFilterSubscriber($builder));

        $builder
            ->add('captcha', 'hidden')
            ->add('place', 'hidden')
            ->add('address', 'hidden')
            ->add('latitude', 'hidden')
            ->add('longitude', 'hidden')
            ->add('day', 'hidden')
            ->add('hour', 'choice', array(
                'label' => 'Heure',
                'choices' => $options['hours'],
                'data' => '19:00:00'
            ))
            ->add('city_id', 'choice', array(
                'label' => 'Ville',
                'choices' => $options['cities']
            ))
            ->add('kind', 'choice', array(
                'label' => 'Type',
                'choices' => $options['kinds']
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
                'captcha'     => new Constraints\Blank(array('message' => "Vous avez été détecté comme un robot.")),
                'place'       => array(
                    new Constraints\NotNull(),
                    new Constraints\Length(array('max' => 100)),
                ),
                'address'     => new Constraints\Length(array('max' => 100)),
                'latitude'    => new Constraints\NotNull(),     // TODO use a DataTransformer to validate theses 2 values
                'longitude'   => new Constraints\NotNull(),
                'day'         => array(
                    new Constraints\NotNull(),
                    new AperophpConstraints\FutureDate(),
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
                'kind'       => array(
                    new Constraints\NotNull(),
                    new Constraints\Choice(array(
                        'choices' => array_keys($this->getKinds())
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
            'kinds'                 => $this->getKinds(),
        ));
    }

    public function getName()
    {
        return 'drink';
    }

    protected function getCities()
    {
        if (null === $this->cities) {
            $this->cities = $this->cityRepository->findAllInAssociativeArray();
        }

        return $this->cities;
    }

    protected function getKinds()
    {
        if (null === $this->kinds) {
            $this->kinds = $this->drinkRepository->findAllKindsInAssociativeArray();
        }

        return $this->kinds;
    }
}
