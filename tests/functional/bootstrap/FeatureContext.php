<?php

use Behat\Behat\Context\BehatContext;
use Behat\MinkExtension\Context\MinkContext;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->useContext('view', new View\WebContext($parameters));
        $this->useContext('homepage', new Context\HomepageContext($parameters));
        $this->useContext('mink', new MinkContext($parameters));
    }
}
