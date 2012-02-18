<?php

namespace Aperophp\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Validator\Constraints;

use Doctrine\DBAL\Connection;

/**
 * Drink form.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 6 févr. 2012 
 * @version 1.1 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class DrinkType extends AbstractType
{
    protected
        $cities,
        $hours;
    
    public function __construct(Connection $connection)
    {
        $this->cities = \Aperophp\Model\City::findAll($connection);
        
        // Build hour choices
        $this->hours = array();
        
        $oStartDate = new \DateTime('2000-01-01');
        $oEndDate = new \DateTime('2000-01-02');
        
        do 
        {
            $this->hours[$oStartDate->format('H:i:s')] = $oStartDate->format('H\hi');
            $oStartDate->add(new \DateInterval('PT30M'));
        }
        while ($oStartDate < $oEndDate);
        
        
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('place', 'hidden')
            ->add('address', 'hidden')
            ->add('latitude', 'hidden')
            ->add('longitude', 'hidden')
            ->add('day', 'hidden')
            ->add('hour', 'choice', array('label' => 'Heure', 'choices' => $this->hours))
            ->add('id_city', 'choice', array('label' => 'Ville', 'choices' => $this->cities))
            ->add('description', 'textarea', array('label' => 'Description'));
    }
    
    public function getDefaultOptions(array $options)
    {
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => array(
                'place' => array(
                    new Constraints\NotNull(),
                    new Constraints\MaxLength(array('limit' => 100)),
                ),
                'address' => new Constraints\MaxLength(array('limit' => 100)),
                'latitude' => new Constraints\Min(array('limit' => 0)),
                'longitude' => new Constraints\Min(array('limit' => 0)),
                'day' => array(
                    new Constraints\NotNull(),
                    new Constraints\Date(),
                ),
                'hour' => array(
                    new Constraints\NotNull(),
                    new Constraints\Time(),
                ),
                'id_city' => array(
                    new Constraints\NotNull(),
                    new Constraints\Choice(array('choices' => array_keys($this->cities))),
                ),
                'description' => new Constraints\NotNull(),
            ),
            'allowExtraFields' => false,
        ));
    
        return array('validation_constraint' => $collectionConstraint);
    }
    
    public function getName()
    {
        return 'drink';
    }
}