<?php

namespace Aperophp\Test\Functional\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject;

class Homepage extends PageObject\Page
{
    /**
     * Page url
     * 
     * @var string
     */
    protected $path = '/';

    protected $elements = array(
        'Blocks' => array('css' => 'div.widgets'),
    );

    /**
     * Return all blocks elements on the page
     * 
     * @return array
     */
    protected function getAllBlocks()
    {
        return $this->findall('css', 'div.widget');
    }

    /**
     * Return the block defined by its name
     * 
     * @param  string $title Block title
     * @return Element
     */
    public function getBlock($title)
    {
        foreach ($this->getAllBlocks() as $block) {
            if ($block->find('css', '.widget-header')->getText() == $title) {
                return $block;
            }
        }
        
        return null;
    }

    /**
     * Check if a block exists with the given title
     * 
     * @param  string  $title Block title
     * @return boolean
     */
    public function hasBlock($title)
    {
        return !is_null($this->getBlock($title));
    }

    /**
     * Get all drinks elements on the page
     * 
     * @return array
     */
    protected function getAllDrinks()
    {
        return $this->findAll('css', 'ul.news-items li');
    }

    /**
     * Check if a drink for given place and date is displayed on the page
     * 
     * @param  string  $date
     * @param  string  $place
     * @return boolean
     */
    public function hasDrink($date, $place)
    {
        foreach ($this->getAllDrinks() as $drink) {
            if (strstr($drink->getText(), $date) && strstr($drink->getText(), $place)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a link with the given text exists in the given block
     * 
     * @param  string  $block      Block name
     * @param  string  $link_label Link label
     * @return boolean
     */
    public function hasAction($block, $link_label)
    {
        $block = $this->getBlock($block);
        if ($block) {
            $links = $block->findAll('css', 'a');
            foreach ($links as $link) {
                if ($link->getText() == $link_label) {
                    return true;
                }
            }
        }

        return false;
    }
}
