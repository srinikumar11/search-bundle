<?php
/**
 * Created by PhpStorm.
 * User: srinivasankumar
 * Date: 22/01/16
 * Time: 11:56 PM
 */
namespace BeeCMS\SearchBundle\Tests\Engine;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchEngineTestCase extends WebTestCase
{
    /** @var \BeeCMS\SearchBundle\Services\SearchInterface  */
    private $searchEngine;

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $_container;

    public function __construct()
    {
        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->_container = $kernel->getContainer();
        $this->searchEngine = $this->get('beecms.search.service');
    }

    protected function get($service)
    {
        return $this->_container->get($service);
    }


    public function testIndex()
    {
        $this->searchEngine->index();
    }

    /**
     * Testing duplicate results
     */
    public function testResults()
    {
        $results = $this->searchEngine->getResults('google');
        $resultCount = 0;

        foreach ($results as $result) {
            $this->assertContains('google', $result['title'], '', true);
            $this->assertContains('GOOGLE', $result['file_contents'], '', true);
            $resultCount++;
        }

        $this->assertEquals(count($results), $resultCount);

    }

    public function testEmptyResults()
    {
        $results = $this->searchEngine->getResults('');
        $resultCount = 0;

        $this->assertEquals(count($results), $resultCount);

    }

    public function testSpecialCharsResults()
    {
        $splCharStringArray = ['/','\\', '$', '@', '#', '!', '!!@#%#$^%^^&^&$%$#%', "o'reilly"];
        foreach ($splCharStringArray as $splCharString) {
            $splCharResults = $this->searchEngine->getResults($splCharString);
            $this->assertEquals(count($splCharResults), $this->getResultsCount($splCharResults, $splCharString));
        }
    }

    private function getResultsCount($results, $searchString)
    {
        $resultCount = 0;
        foreach ($results as $result) {
            $this->assertContains($searchString, $result['title'], '', true);
            $this->assertContains($searchString, $result['file_contents'], '', true);
            $resultCount++;
        }

        return $resultCount;
    }

}