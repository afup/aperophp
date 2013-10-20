<?php

use SensioLabs\Behat\PageObjectExtension\PageObject;

class Homepage extends PageObject\Page
{
    protected $path = '/';

    /**
     * Check if a drink for given place and date is displayed on the page
     * 
     * @param  string  $date
     * @param  string  $place
     * @return boolean
     */
    public function hasDrink($date, $place)
    {
        foreach( $this->getAllDrinks() as $drink ) {
            if( strstr( $drink->getText(), $date ) && strstr( $drink->getText(), $place )) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all drinks element on the page
     * 
     * @return array
     */
    protected function getAllDrinks()
    {
        return $this->findAll('css', 'ul.news-items li');
    }
}


