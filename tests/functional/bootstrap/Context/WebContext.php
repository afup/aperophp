<?php

namespace Aperophp\Test\Functional\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Behat\Context\Step;
use Behat\Gherkin\Node\TableNode;

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;

class WebContext extends BehatContext
{
    protected $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    protected function getMink()
    {
        return $this->getMainContext()->getSubcontext('mink');
    }

    /**
     * @Given /^la page "(?P<url>[^"]*)" s\'affiche correctement$/
     */
    public function laPageSAfficheCorrectement($url)
    {
        return array(
            new Step\Then("je suis sur \"$url\""),
            new Step\Then("le code de status de la réponse devrait être 200"),
        );
    }
}
