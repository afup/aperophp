<?php

namespace Aperophp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Aperophp\Form\EventListener\DataFilterSubscriber;

/**
 *  Participate form.
 *
 *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
 *  @version 1.1 - 22 fev. 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class DrinkParticipationType extends AbstractType
{
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new DataFilterSubscriber($builder));

        if (!$this->session->has('member')) {
            $builder->add(
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

        $builder
            ->add('percentage', 'text', array(
                'label' => 'Poucentage de participation'
            ))
            ->add('reminder', 'checkbox', array(
                'label'    => 'Me rappeler l\'évènement',
                'required' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $fields = array(
            'percentage'   => array(
                new Constraints\Min(array('limit' => 0)),
                new Constraints\Max(array('limit' => 100))
            ),
            'reminder'     => array(),
        );

        if (!$this->session->has('member')) {
            $fields['user'] = new Constraints\Collection(array(
                'lastname' => new Constraints\MaxLength(array('limit' => 80)),
                'firstname' => new Constraints\MaxLength(array('limit' => 80)),
                'email' => array(
                    new Constraints\Email(),
                    new Constraints\NotNull(),
                )
            ));
        }
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => $fields
        ));

        $resolver->setDefaults(array(
            'validation_constraint' => $collectionConstraint,
        ));
    }

    public function getName()
    {
        return 'drink_participate';
    }
}
