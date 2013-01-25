<?php

namespace Aperophp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Aperophp\Form\EventListener\DataFilterSubscriber;

/**
 * Signin form.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 4 févr. 2012
 * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class SigninType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new DataFilterSubscriber($builder));

        $builder
            ->add('username', 'text', array('label' => 'Identifiant'))
            ->add('password', 'password', array('label' => 'Mot de passe'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => array(
                'username' => array(
                    new Constraints\Length(array('max' => 80)),
                    new Constraints\NotNull(),
                ),
                'password' => array(
                    new Constraints\Length(array('max' => 80)),
                    new Constraints\NotNull(),
                ),
            ),
            'allowExtraFields' => false,
        ));

        $resolver->setDefaults(array(
            'validation_constraint' => $collectionConstraint
        ));
    }

    public function getName()
    {
        return 'signin';
    }
}
