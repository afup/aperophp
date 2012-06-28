<?php

namespace Aperophp\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Validator\Constraints;

/**
 *  Participate form.
 *
 *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
 *  @version 1.1 - 22 fev. 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class DrinkParticipationType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $defaultOptions = $options['user'] ? array('disabled' => '') : array();
        $builder
                    ->add('user_id', 'hidden')
                    ->add('lastname', 'text',   array(
                                                        'label' => 'Nom',
                                                        'required' => false,
                                                        'attr' => array('placeholder' => 'Facultatif.')
                                                ) + $defaultOptions)
                    ->add('firstname', 'text', array(
                                                        'label' => 'Prénom',
                                                        'required' => false,
                                                        'attr' => array('placeholder' => 'Facultatif.')
                                               ) + $defaultOptions)
                    ->add('email', 'email', array('attr' => $defaultOptions))
                    ->add('percentage', 'text',
                                                        array('label' => 'Poucentage de participation')
                                                        + $defaultOptions)
                    ->add('reminder', 'checkbox',
                                                        array(
                                                                'label' => 'Me rappeler l\'évènement',
                                                                'required' => false
                                                        )
                                                        + $defaultOptions);
    }

    public function getDefaultOptions(array $options)
    {
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => array(
                'user_id'           => new Constraints\Min(array('limit' => 0)),
                'lastname'          => new Constraints\MaxLength(array('limit' => 80)),
                'firstname'         => new Constraints\MaxLength(array('limit' => 80)),
                'email'             => array(
                                                new Constraints\Email(),
                                                new Constraints\NotNull(),
                                       ),
                'percentage'        => array(
                                                new Constraints\Min(array('limit' => 0)),
                                                new Constraints\Max(array('limit' => 100))
                                       ),
                'reminder'          => array(),
//                'csrf_protection'   => true,
//                'csrf_field_name'   => '_token',
//                'intention'         => 'drink_participation'
            ),
            'allowExtraFields'      => false,
        ));

        return array(
                        'validation_constraint' => $collectionConstraint,
                        'user' => $options['user']
               );
    }

    public function getName()
    {
        return 'drink_participate';
    }
}
