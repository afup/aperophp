<?php

namespace Aperophp\Test\Functional\Context;

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Behat\Behat\Exception\PendingException;
use Behat\Behat\Context\Step;

class SignupContext extends PageObjectContext
{
    /**
     * @Given /^je vais sur la page d\'inscription$/
     */
    public function jeVaisSurLaPageDInscription()
    {
        $this->getPage('Signup')->open();
    }

    /**
     * @Given /^la page d\'inscription s\'affiche correctement$/
     */
    public function laPageDInscriptionSAfficheCorrectement()
    {
        return array(
            new Step\Then(sprintf('la page "%s" s\'affiche correctement', $this->getPage('Signup')->path)),
        );
    }

    /**
     * @Given /^je me créé un compte "([^"]*)"\/"([^"]*)"\/"([^"]*)"$/
     */
    public function jeMeCreeUnCompte($login, $password, $email)
    {
        $this->getPage('Signup')->createAccount($login, $password, $email);
    }
}
