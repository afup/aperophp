<?php

namespace Aperophp\Test;

use Symfony\Component\HttpKernel\Client as BaseClient;

class Client extends BaseClient
{
    public function connect($username = 'user', $password = 'user')
    {
        $crawler = $this->request('GET', '/member/signin.html');

        if (!$this->getResponse()->isOk()) {
            return false;
        }

        $form = $crawler->selectButton('login')->form();

        $crawler = $this->submit($form, array(
            'signin[username]' => $username,
            'signin[password]' => $password,
        ));

        if (!$this->getResponse()->isRedirect('/drink/')) {
            return false;
        }

        $crawler = $this->followRedirect();

        if (1 !== $crawler->filter('a.dropdown-toggle:contains("Bienvenue, user")')->count()) {
            return false;
        }

        return true;
    }
}
