<?php

namespace Aperophp\Test\Functional\Page\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

/**
 * Global menu element
 */
class Menu extends Element
{
    /**
     * Element css selector
     * @var array
     */
    protected $selector = array('css' => 'ul.mainnav');

    /**
     * Return an array of links in the menu
     * 
     * @return array
     */
    public function getLinks()
    {
        return $this->findAll('css', 'li a');
    }

    /**
     * Give the number of links in the menu
     * 
     * @return integer
     */
    public function countLinks()
    {
        return count($this->getLinks());
    }

    /**
     * Check if a menu link exist for the given label and given url
     * 
     * @param  string  $label Link label
     * @param  string  $url   Link url
     * @return boolean
     */
    public function isLinkExists($label, $url)
    {
        foreach ($this->getLinks() as $link) {
            if ($label == $link->getText() && strstr($link->getAttribute('href'), $url)) {
                return true;
            }
        }

        return false;
    }
}
