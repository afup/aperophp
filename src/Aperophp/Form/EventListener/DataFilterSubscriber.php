<?php

namespace Aperophp\Form\EventListener;

use Symfony\Component\Form\Event\FilterDataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * DataFilterSubscriber
 *
 * This subscriber delete all extra data on PRE_SET_DATA event.
 * It's used to avoid extra data errors (if allow_extra_fields = false) or sql
 * errors (if allow_extra_fields = true and data contains inexisting sql fields)
 *
 * @author Olivier Dolbeau <contact@odolbeau.fr>
 */
class DataFilterSubscriber implements EventSubscriberInterface
{
    protected $builder;

    public function __construct(FormBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FilterDataEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $data = $this->filterData($data);

        $event->setData($data);
    }

    protected function filterData($data, $builder = null)
    {
        if (null == $builder) {
            $builder = $this->builder;
        }

        $filteredData = array();
        foreach ($data as $key => $value) {
            if (!$builder->has($key)) {
                continue;
            }

            if (is_array($value)) {
                $filteredData[$key] = $this->filterData($data[$key], $builder->get($key));
            } else {
                $filteredData[$key] = $data[$key];
            }
        }

        return $filteredData;
    }
}
