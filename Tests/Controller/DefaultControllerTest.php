<?php

namespace BeeCMS\SearchBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<h1>Simple Search</h1>', $client->getResponse()->getContent());

    }

    public function testResult()
    {
        $queryClient = static::createClient();
        $crawler2 = $queryClient->request('GET', '/?query=a');
        $this->assertEquals(200, $queryClient->getResponse()->getStatusCode());

        $result_list = $crawler2->filter('ul#result-list');
        $countDisplay = $crawler2->filter('span#result-count')->text();

        $this->assertContains('<h2>Results: Count', $queryClient->getResponse()->getContent());
        $this->assertEquals($result_list->children()->count(), $countDisplay);

    }

    public function testResult2()
    {
        $queryClient = static::createClient();
        $crawler2 = $queryClient->request('GET', '/?query=google');
        $this->assertEquals(200, $queryClient->getResponse()->getStatusCode());

        $result_list = $crawler2->filter('ul#result-list');
        $countDisplay = $crawler2->filter('span#result-count')->text();

        $this->assertContains('<h2>Results: Count', $queryClient->getResponse()->getContent());
        $this->assertEquals($result_list->children()->count(), $countDisplay);

    }
}
