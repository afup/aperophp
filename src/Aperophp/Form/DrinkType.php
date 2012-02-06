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
 * @version 1.0 - 6 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class DrinkType extends AbstractType
{
    protected
        $cities;
    
    public function __construct(Connection $connection)
    {
        $this->cities = \Aperophp\Model\City::findAll($connection);
    }
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('place', 'text', array('label' => 'Lieu'))
            ->add('day', 'text', array('label' => 'Jour'))
            ->add('hour', 'text', array('label' => 'Heure'))
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