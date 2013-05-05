<?php

use SensioLabs\Behat\PageObjectExtension\PageObject;

class Homepage extends PageObject\Page
{
    protected $path = '/';

    protected $elements = array(
        'Menu'  => array('css' => 'ul.mainnav'),
    );

    public function hasMenu(array $menu_content)
    {
        $menu = $this->getElement('Menu');
        var_dump(array_diff($menu_content, $menu->toArray()));
    }
}


