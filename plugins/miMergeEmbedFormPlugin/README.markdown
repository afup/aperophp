miMergeEmbedFormPlugin
============
Author: Miguel Ibero <miguel.ibero.carreras@gmail.com>

Plugin to merge embedded forms for Symfony 1.4

Symfony forms can be combined using two methods:
* embedForm: will add a child form with a name prefix:
    embedForm('item',ItemForm) -> section[item][item_id]
  put adding a decorator around separating the child
  form fields from the rest of the form
* mergeForm: will add a child form without a prefix:
    mergeForm(ItemForm) section[item_id]
  and without a decorator

mergeEmbedForm adds a child form with a name prefix,
but without a decorator around the child form. This
way the child fields look exactly like parent
form fields.

The code is inspired by this blogpost:
http://www.blogs.uni-osnabrueck.de/rotapken/2009/03/13/symfony-merge-embedded-form/

Installation
------------

1. copy plugin to plugins/ directory
2. Add plugin to config/ProjectConfiguration.class.php

Configuration
-------------

    class SectionForm extends BaseSectionForm
    {
      public function configure()
      {
        $form = new ItemForm($this->getObject()->getItem());
        $this->mergeEmbedForm('item',$form);
      }
    }
   
ChangeLog
---------

* 1.2 10/09/2010 added code to fix showing errors of mergeembedded forms
* 1.1 02/09/2010 rewrote code to use symfony event dispatcher, subclassing BaseDoctrineForm not needed anymore
* 1.0 19/07/2010
