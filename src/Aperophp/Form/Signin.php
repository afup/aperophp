<?php

namespace Aperophp\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Validator\Constraints;

/**
 * Signin form.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 4 févr. 2012 
 * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class Signin extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username', 'text', array('label' => 'Identifiant'))
            ->add('password', 'password', array('label' => 'Mot de passe'));
    }
    
    public function getDefaultOptions(array $options)
    {
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => array(
                'username'     => array(
                    new Constraints\MaxLength(array('limit' => 80)),
                    new Constraints\NotNull(),
                ),
                'password'     => array(
                    new Constraints\MaxLength(array('limit' => 80)),
                    new Constraints\NotNull(),
                ),
            ),
            'allowExtraFields' => false,
        ));
    
        return array('validation_constraint' => $collectionConstraint);
    }
    
    public function getName()
    {
        return 'signin';
    }
}