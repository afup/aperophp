<?php

namespace Aperophp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Aperophp\Form\EventListener\DataFilterSubscriber;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Drink comment form.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 18 févr. 2012
 * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class DrinkCommentType extends AbstractType
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
                    'required' => true
                ))
                ->add('email', 'email')
            );
        }

        $builder
            ->add('content', 'textarea', array(
                'label' => 'Commentaire'
            ))
            ->add('captcha', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $fields = array(
            'captcha' => new Constraints\Blank(array('message' => "Vous avez été détecté comme un robot.")),
            'content' => new Constraints\NotNull(),
        );

        if (!$this->session->has('member')) {
            $fields['user'] = new Constraints\Collection(array(
                'lastname' => new Constraints\Length(array('max' => 80)),
                'firstname' => new Constraints\Length(array('max' => 80)),
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
        return 'drink_comment';
    }
}
