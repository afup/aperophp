<?php

namespace Aperophp\Test\Functional\Context;

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Behat\Behat\Exception;
use Behat\Behat\Context\Step;
use Behat\Gherkin\Node\TableNode;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;

class HomepageContext extends PageObjectContext
{
    /**
     * @Given /^le menu est affiché$/
     */
    public function leMenuEstAffiche()
    {
        try {
            $this->getPage('Homepage')->getElement('Menu');
        } catch (ElementNotFoundException $e) {
            throw new \LogicException('Le menu n\'est pas affiché');
        }
    }

    /**
     * @Given /^le menu contient ces éléments:$/
     */
    public function leMenuContientCesElements(TableNode $table)
    {
        $expected_menus = $table->getHash();
        $nb_menus = $this->getPage('Homepage')->getElement('Menu')->countLinks();

        // Exactly same number of link allow to check if there is no extra elements
        if (count($expected_menus) !== $nb_menus) {
            throw new \LogicException(
                sprintf(
                    'Le nombre d\'éléments attendus (%d) ne correspond pas au nombre d\'élément trouvés (%s)',
                    count($expected_menus),
                    $nb_menus
                )
            );
        }

        // Each link correspond to expected one
        foreach ($expected_menus as $expected_menu) {
            $label = $expected_menu['libellé'];
            $link = $expected_menu['lien'];

            if (!$this->getPage('Homepage')->getElement('Menu')->isLinkExists($label, $link)) {
                throw new \LogicException(
                    sprintf(
                        'Le menu \'%s\' (%s) n\'est pas présent sur la page',
                        $label,
                        $link
                    )
                );
            }
        }
    }

    /**
     * @Given /^le bloc "([^"]*)" est visible$/
     */
    public function leBlocEstVisible($bloc_name)
    {
        if (!$this->getPage('Homepage')->hasBlock($bloc_name)) {
            throw new \LogicException(sprintf('Impossible de trouver le bloc "%s"', $bloc_name));
        }
    }

    /**
     * @Given /^l\'apéritif du "([^"]*)" à "([^"]*)" est visible$/
     */
    public function lAperitifDuAEstVisible($date, $place)
    {
        if (!$this->getPage('Homepage')->hasDrink($date, $place)) {
            throw new \LogicException("Impossible de trouver l'apéritif");
        }
    }

    /**
     * @Given /^l\'apéritif du "([^"]*)" à "([^"]*)" n\'est pas visible$/
     */
    public function lAperitifDuANEstPasVisible($date, $place)
    {
        if ($this->getPage('Homepage')->hasDrink($date, $place)) {
            throw new \LogicException("L'apéritif est affiché sur la page");
        }
    }

    /**
     * @Given /^il est possible de créer un apéro$/
     */
    public function ilEstPossibleDeCreerUnApero()
    {
        if (!$this->getPage('Homepage')->hasAction('Les apéros PHP', 'Organiser un apéro »')) {
            throw new \LogicException('Le lien de création d\'un apéro n\'est pas disponible');
        }
    }
}
