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
use BeeCMS\SearchBundle\Services\SearchService;

class SearchIndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('search:generate-index')
            ->setDescription('Create or update the search index')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
        $output->getFormatter()->setStyle('fire', $style);

        $style = new OutputFormatterStyle('green', 'default', array('bold'));
        $output->getFormatter()->setStyle('start', $style);

        $style = new OutputFormatterStyle('black', 'default', array('bold'));
        $output->getFormatter()->setStyle('end', $style);

        $searchEngine =  new SearchEngine($this->getContainer()->get('doctrine')->getManager(), array());

        $output->writeln('<fire>Clearing existing index</fire>');
        $searchEngine->clearIndex();
        $output->writeln('<start>Indexing started</start>');
        $searchEngine->index();
        $output->writeln('<end>Index generated successfully</end>');
    }


}