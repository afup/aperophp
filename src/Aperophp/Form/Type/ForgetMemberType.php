<?php

namespace Aperophp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Aperophp\Form\EventListener\DataFilterSubscriber;

/**
 * Forget form.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 4 févr. 2012
 * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class ForgetMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new DataFilterSubscriber($builder));

        $builder->add('email', 'email');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Constraints\Collection(array(
            'fields' => array(
                'email'        => array(
                    new Constraints\NotBlank(),
                    new Constraints\Email(),
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
        return 'member_forget';
    }
}
