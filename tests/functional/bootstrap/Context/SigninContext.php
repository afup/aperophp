<?php

namespace Aperophp\Test\Functional\Context;

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Behat\Behat\Exception\PendingException;
use Behat\Behat\Context\Step;

class SigninContext extends PageObjectContext
{
    /**
     * @Given /^je vais sur la page de connexion$/
     */
    public function jeVaisSurLaPageDeConnexion()
    {
        $this->getPage('Signin')->open();
    }

    /**
     * @Given /^la page de connexion s\'affiche correctement$/
     */
    public function laPageDeConnexionSAfficheCorrectement()
    {
        return array(
            new Step\Then(sprintf('la page "%s" s\'affiche correctement', $this->getPage('Signin')->path)),
        );
    }

    /**
     * @Given /^je me connecte avec les identifiants "([^"]*)"\/"([^"]*)"$/
     */
    public function jeMeConnecteAvecLesIdentifiants($login, $password)
    {
        
    }
}
