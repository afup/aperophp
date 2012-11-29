<?php

namespace Aperophp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Aperophp\Form\EventListener\DataFilterSubscriber;
use Aperophp\Repository;

/**
 *  Participate form.
 *
 *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
 *  @version 1.1 - 22 fev. 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class DrinkParticipationType extends AbstractType
{
    protected $session;
    protected $drinkParticipantRepository;
    protected $presences = null;

    public function __construct(SessionInterface $session, Repository\DrinkParticipant $drinkParticipantRepository)
    {
        $this->session = $session;
        $this->drinkParticipantRepository = $drinkParticipantRepository;
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
                    'required' => true
                ))
                ->add('email', 'email')
            );
        }

        $builder
            ->add('percentage', 'choice', array(
                'label' => 'Participation',
                'choices' => $options['presences'],
                'attr' => array(
                    'size' => count($this->getPresences())
                )
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
                new Constraints\NotNull(),
                new Constraints\Choice(array(
                    'choices' => array_keys($this->getPresences())
                )),
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
            'presences'             => $this->getPresences(),
        ));
    }

    public function getName()
    {
        return 'drink_participate';
    }

    protected function getPresences()
    {
        if (null === $this->presences) {
            $this->presences = $this->drinkParticipantRepository->findAllPresencesInAssociativeArray();
        }

        return $this->presences;
    }
}
