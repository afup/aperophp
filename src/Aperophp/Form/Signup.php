<?php

namespace Aperophp\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Validator\Constraints;

/**
 * Signup form.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 22 janv. 2012 
 * @version 1.0 - 22 janv. 2012 - Koin <pkoin.koin@gmail.com>
 */
class Signup extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('lastname', 'text')
            ->add('firstname', 'text')
            ->add('username', 'text')
            ->add('email', 'email')
            ->add('password', 'password');
    }
    
    public function getDefaultOptions(array $options)
    {
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => array(
                'lastname'     => new Constraints\MaxLength(array('limit' => 80)),
                'firstname'    => new Constraints\MaxLength(array('limit' => 80)),
                'username'     => new Constraints\MaxLength(array('limit' => 80)),
                'email'        => new Constraints\Email(),
                'password'     => new Constraints\NotNull(),
            ),
            'allowExtraFields' => false,
        ));
    
        return array('validation_constraint' => $collectionConstraint);
    }
    
    public function getName()
    {
        return 'signup';
    }
}