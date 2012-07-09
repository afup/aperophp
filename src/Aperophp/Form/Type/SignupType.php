<?php

namespace Aperophp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Aperophp\Form\EventListener\DataFilterSubscriber;

/**
 * Signup form.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 22 janv. 2012
 * @version 1.0 - 22 janv. 2012 - Koin <pkoin.koin@gmail.com>
 */
class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new DataFilterSubscriber($builder));

        $builder->add(
            $builder->create('member', 'form')
            ->add('username', 'text', array(
                'label' => 'Identifiant'
            ))
            ->add('password', 'password', array(
                'label' => 'Mot de passe'
            ))
        )->add(
            $builder->create('user', 'form')
            ->add('lastname', 'text', array(
                'label'    => 'Nom',
                'required' => false,
                'attr'     => array(
                    'placeholder' => 'Facultatif.'
                )
            ))
            ->add('firstname', 'text', array(
                'label'    => 'Prénom',
                'required' => false,
                'attr'     => array(
                    'placeholder' => 'Facultatif.'
                )
            ))
            ->add('email', 'email')
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => array(
                'user' => new Constraints\Collection(array(
                    'fields' => array(
                        'lastname'     => new Constraints\MaxLength(array('limit' => 80)),
                        'firstname'    => new Constraints\MaxLength(array('limit' => 80)),
                        'email'        => array(
                            new Constraints\NotBlank(),
                            new Constraints\Email(),
                        ),
                    ),
                )),
                'member' => new Constraints\Collection(array(
                    'fields' => array(
                        'username'     => array(
                            new Constraints\NotBlank(),
                            new Constraints\MaxLength(array('limit' => 80)),
                        ),
                        'password'     => array(
                            new Constraints\NotBlank(),
                            new Constraints\MinLength(array('limit' => 4)),
                            new Constraints\MaxLength(array('limit' => 80)),
                        ),
                    )
                ))
            ),
        ));

        $resolver->setDefaults(array(
            'validation_constraint' => $collectionConstraint
        ));
    }

    public function getName()
    {
        return 'signup';
    }
}
