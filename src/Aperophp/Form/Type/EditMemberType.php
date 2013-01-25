<?php

namespace Aperophp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Aperophp\Form\EventListener\DataFilterSubscriber;

/**
 * Edit member form.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 4 févr. 2012
 * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class EditMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new DataFilterSubscriber($builder));

        $builder->add(
            $builder->create('member', 'form')
            ->add('password', 'password', array(
                'label'    => 'Mot de passe',
                'required' => false
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
                        'lastname'  => new Constraints\Length(array('max' => 80)),
                        'firstname' => new Constraints\Length(array('max' => 80)),
                        'email'     => array(
                            new Constraints\Email(),
                            new Constraints\NotBlank(),
                        ),
                    )
                )),
                'member' => new Constraints\Collection(array(
                    'fields' => array(
                        'password' => new Constraints\Length(array('max' => 80)),
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
        return 'member_edit';
    }
}
