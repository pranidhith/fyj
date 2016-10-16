<?php
namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginSeeker(){

        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $button= $crawler->selectButton('Log in');
        $form = $button->form(array(
            '_username' => 'pani',
            '_password' => '1234567',
        ),'POST');
        $client->submit($form);
        $client->getResponse()->getTargetUrl();
        $client->followRedirect();
        $client->followRedirect();
        $this->assertRegExp('/seeker%1homepage/', $client->getRequest()->getUri());

    }

    public function testLoginAdmin(){

        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $button= $crawler->selectButton('Log in');
        $form = $button->form(array(
            '_username' => 'admin',
            '_password' => '123456',
        ),'POST');
        $client->submit($form);
        $client->getResponse()->getTargetUrl();
        $client->followRedirect();
        $client->followRedirect();
        $this->assertRegExp('/admin_homepage/', $client->getRequest()->getUri());

    }
}