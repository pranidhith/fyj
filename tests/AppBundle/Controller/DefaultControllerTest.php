<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $heading = $crawler->filter('h1')->eq(0)->text();
        $this->assertEquals('Find Your Job - Online Job management System', $heading);


    }
}