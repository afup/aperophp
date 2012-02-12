<?php

namespace Aperophp\Form\Participate;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Validator\Constraints;

/**
 *  Delete a participation form for anonymous users.
 *
 *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
 *  @version 1.0 - 12 fev. 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class DeleteAnonymousType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder    ->add('drink', 'hidden')
                    ->add('email', 'email')
                    ->add('token', 'text', array('label' => 'Jeton'));
    }
    
    public function getDefaultOptions(array $options)
    {
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => array(
                'drink'         => new Constraints\NotNull(),
                'email'         => array(
                    new Constraints\Email(),
                    new Constraints\NotNull(),
                ),
                'token'         => new Constraints\NotNull()
            ),
            'allowExtraFields'  => false,
        ));
    
        return array('validation_constraint' => $collectionConstraint);
    }
    
    public function getName()
    {
        return 'participate_delete_anonymous';
    }
}
