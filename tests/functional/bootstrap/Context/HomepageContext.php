<?php

namespace Context;

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Behat\Behat\Exception;
use Behat\Behat\Context\Step;
use Behat\Gherkin\Node\TableNode;

class HomepageContext extends PageObjectContext
{
    /**
     * @Given /^le lien "([^"]*)" est visible$/
     */
    public function leLienEstVisible($link_text)
    {
        return array(
            new Step\Then("je devrais voir \"".$link_text."\""),
        );
    }

    /**
     * @Given /^le menu est affiché$/
     */
    public function leMenuEstAffiche()
    {
        return array(
            new Step\Then("je devrais voir l'élément \"ul.mainnav\""),
        );
    }

    /**
     * @Given /^le menu contient ces éléments:$/
     */
    public function leMenuContientCesElements(TableNode $table)
    {
        $steps = array();

        foreach( $table as $line ) {
            $steps[] = new Step\Then("je devrais voir \"".$line['libellé']."\" dans l'élément \"ul.mainav li i[href=".$line['lien']."] span\"");
        }

        return $steps;
    }

    /**
     * @Given /^le bloc "([^"]*)" est visible$/
     */
    public function leBlocEstVisible($bloc_name)
    {
        return array(
            new Step\Then("je devrais voir \"".$bloc_name."\""),
        );
    }

    /**
     * @Given /^l\'apéritif du "([^"]*)" à "([^"]*)" est visible$/
     */
    public function lAperitifDuAEstVisible($date, $place)
    {
        if( !$this->getPage('Homepage')->hasDrink($date, $place) ) {
            throw new \LogicException("L'apéritif du $date à $place est introuvable sur la page");
        }
    }

    /**
     * @Given /^l\'apéritif du "([^"]*)" à "([^"]*)" n\'est pas visible$/
     */
    public function lAperitifDuANEstPasVisible($date, $place)
    {
        if( $this->getPage('Homepage')->hasDrink($date, $place) ) {
            throw new \LogicException("L'apéritif du $date à $place est présent sur la page");
        }
    }
}
