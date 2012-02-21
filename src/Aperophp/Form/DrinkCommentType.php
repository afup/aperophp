<?php

namespace Aperophp\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Validator\Constraints;

use Doctrine\DBAL\Connection;

/**
 * Drink comment form.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 18 févr. 2012 
 * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class DrinkCommentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        // If user is authenticated, "lastname", "firstname", "email" are disabled.
        $defaultOptions = $options['user'] ? array('disabled' => '') : array();
        
        $builder
            ->add('id_user', 'hidden')
            ->add('lastname', 'text', array('label' => 'Nom', 'required' => false, 'attr' => array('placeholder' => 'Facultatif.') + $defaultOptions))
            ->add('firstname', 'text', array('label' => 'Prénom', 'required' => false, 'attr' => array('placeholder' => 'Facultatif.') + $defaultOptions))
            ->add('email', 'email', array('attr' => $defaultOptions))
            ->add('content', 'textarea', array('label' => 'Commentaire'));
    }
    
    public function getDefaultOptions(array $options)
    {
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => array(
                'id_user'      => new Constraints\Min(array('limit' => 0)),
                'lastname'     => new Constraints\MaxLength(array('limit' => 80)),
                'firstname'    => new Constraints\MaxLength(array('limit' => 80)),
                'email'        => array(
                    new Constraints\Email(),
                    new Constraints\NotNull(),
                ),
                'content'      => new Constraints\NotNull(),
            ),
            'allowExtraFields' => false,
        ));
    
        return array(
            'validation_constraint' => $collectionConstraint,
            'csrf_protection' => false,
            'user' => $options['user'],
        );
    }
    
    public function getName()
    {
        return 'drink_comment';
    }
}