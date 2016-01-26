<?php
/**
 * Created by PhpStorm.
 * User: srinivasankumar
 * Date: 23/01/16
 * Time: 2:26 AM
 */


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ModelTest extends WebTestCase
{
    protected $_application;

    public function getContainer()
    {
        return $this->_application->getKernel()->getContainer();
    }
    public function setUp()
    {
        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->_application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $this->_application->setAutoExit(false);
        $this->runConsole("doctrine:schema:drop", array("--force" => true));
        $this->runConsole("doctrine:schema:create");
    }
    protected function runConsole($command, Array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));
        return $this->_application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }

    public function testBuildEnv()
    {
        $this->setUp();
    }
}