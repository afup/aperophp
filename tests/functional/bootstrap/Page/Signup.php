<?php

namespace Aperophp\Test\Functional\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject;

class Signup extends PageObject\Page
{
    /**
     * Page url
     * 
     * @var string
     */
    public $path = '/member/signup.html';

    /**
     * Page elements
     * 
     * @var array
     */
    protected $elements = array(
        'form' => array('css' => 'form#signup')
    );

    /**
     * Create an account
     * 
     * @param  string $username
     * @param  string $password
     * @param  string $email
     * @param  string $firstname
     * @param  string $lastname
     * 
     * @return void
     */
    public function createAccount($username, $password, $email, $firstname=null, $lastname=null)
    {
        $signupForm = $this->getElement('form');

        $signupForm->fillField('signup_member_username', $username);
        $signupForm->fillField('signup_member_password', $password);
        $signupForm->fillField('signup_user_email', $email);
        $signupForm->fillField('signup_user_lastname', $lastname);
        $signupForm->fillField('signup_user_firstname', $firstname);

        return $signupForm->pressButton('S\'inscrire');
    }
}
