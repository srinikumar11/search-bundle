<?php
/**
 * Created by PhpStorm.
 * @author Srinivasan Kumar <srinikumar11@gmail.com>
 * Date: 22/01/16
 * Time: 6:06 PM
 */

namespace BeeCMS\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use BeeCMS\SearchBundle\Services\FileSearchService;
use BeeCMS\SearchBundle\Services\FileSearchWithDBIndexService;

class SetupCommand extends ContainerAwareCommand
{
    /** @var  Application */
    protected $_application;

    protected function configure()
    {
        $this
            ->setName('search:setup')
            ->setDescription('Create or update the search index')
        ;
    }

    public function getContainer()
    {
        return $this->_application->getKernel()->getContainer();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
        $output->getFormatter()->setStyle('fire', $style);

        $style = new OutputFormatterStyle('green', 'default', array('bold'));
        $output->getFormatter()->setStyle('start', $style);

        $style = new OutputFormatterStyle('black', 'default', array('bold'));
        $output->getFormatter()->setStyle('end', $style);

        $style = new OutputFormatterStyle('black', 'green', array('bold'));
        $output->getFormatter()->setStyle('server', $style);

        $kernel = new \AppKernel("prod", true);
        $kernel->boot();
        $this->_application = new Application($kernel);
        $this->_application->setAutoExit(false);
//        $this->runConsole("doctrine:database:drop");
//        $this->runConsole("doctrine:database:create");
//        $this->runConsole("doctrine:schema:drop", array("--force" => true));
        $this->runConsole("doctrine:schema:update", array("--force" => true));
        $this->index($output);
//        $output->writeln('<server> [OK] Server running on http://127.0.0.1:8000  </server>');
//        $this->runConsole("server:run");


    }

    protected function runConsole($command, Array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));
        return $this->_application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }

    protected function index($output)
    {
        $searchEngine =  new FileSearchWithDBIndexService($this->getContainer()->get('doctrine')->getManager(), new FileSearchService());
        $output->writeln('<start>Indexing started</start>');
        $searchEngine->index();
        $output->writeln('<end>Index generated successfully</end>');
    }


}