<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testCreateVacancy(){
        $client = static::createClient();

        $crawler = $client->request('GET', 'jobRecruiter_home');

        $link = $crawler->selectLink('vacancy_home')->link();
        $viewAdmin = $client->click($link);
        $client->followRedirect();
        $button = $viewAdmin->selectButton('create Vacancy');
        $form = $button->form(array(
            'place'=> 'test',
            'position' => 'male',
            'salaryGiven'=>'high',
            'closingDate'=>'12/04/2016',
        ), 'POST'
        );
        $client->submit($form);
        $client->click($button);
        $client->getResponse()->getTargetUrl();
        $client->followRedirect();
        $this->assertRegExp('/vacancy/viewcompany/', $client->getRequest()->getUrl());



    }
}