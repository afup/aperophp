<?php

use Behat\Behat\Context\BehatContext;
use Behat\MinkExtension\Context\MinkContext;

use Aperophp\Test\Functional\Context;
use Aperophp\Database\Tool as DatabaseTool;

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
        $this->useContext('view', new Context\WebContext($parameters));
        $this->useContext('homepage', new Context\HomepageContext($parameters));
        $this->useContext('signup', new Context\SignupContext($parameters));
        $this->useContext('signin', new Context\SigninContext($parameters));
        $this->useContext('mink', new MinkContext($parameters));
    }

    /**
     * Reset database for testing on fresh fixtures import
     *
     * @BeforeFeature
     */
    public static function resetDatabase()
    {
        // Load application to have db connection
        $app = require __DIR__.'/../../../app/bootstrap.php';
        require __DIR__.'/../../../app/config_behat.php';

        // Reseting database
        $databaseTool = new DatabaseTool($app['db']);
        $databaseTool->createSchema(__DIR__.'/../../../data/sql/schema.mysql.sql');
        $databaseTool->loadFixtures(__DIR__.'/../../../data/sql/fixtures.sql');
    }
}
