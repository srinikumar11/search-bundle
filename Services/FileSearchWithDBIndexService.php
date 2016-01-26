<?php
/**
 * Created by PhpStorm.
 * @author Srinivasan Kumar <srinikumar11@gmail.com>
 * Date: 22/01/16
 * Time: 6:06 PM
 */

namespace BeeCMS\SearchBundle\Services;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Finder\Finder;
use TVSI\SearchBundle\Entity\SearchIndex;
use TVSI\SearchBundle\Services\SearchInterface;

class FileSearchWithDBIndexService implements SearchInterface
{
    /** @var  EntityManager */
    private $entityManager;

    /** @var  FileSearchService */
    private $searchService;

    /**
     * @param EntityManager $entityManager
     * @param FileSearchService $searchService
     */
    public function __construct(EntityManager $entityManager, FileSearchService $searchService)
    {
        $this->entityManager = $entityManager;
        $this->searchService = $searchService;
    }

    /**
     * {@inheritdoc}
     */
    public function index()
    {
        $style = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
        $output = new ConsoleOutput();
        $output->getFormatter()->setStyle('fire', $style);

        $fileContents = $this->searchService->getFileContents();

        foreach ($fileContents as $content) {
            $output->writeln('<info>Indexing File & Contents: </info><comment>'.$content['path'].'</comment>');
            /**
             * @var \Symfony\Component\Finder\SplFileInfo $file
             */
            $this->createIndex($content['title'], $content['file_contents'],$content['path']);
        }
    }

    /**
     * @param $title
     * @param $content
     * @param $path
     * @return SearchIndex
     */
    public function createIndex($title, $content, $path)
    {
        /** @var SearchIndex $index */
        $index = $this->getManager()
            ->getRepository('TVSISearchBundle:SearchIndex')
            ->findOneBy(array('title' => $title));

        if (!$index instanceof SearchIndex) {
            $index = new SearchIndex();
        }

        $index->setTitle($title);
        $index->setContent($content);
        $index->setPath($path);
        $this->getManager()->persist($index);
        $this->getManager()->flush();

        return $index;

    }

    /**
     * @return EntityManager
     */
    private function getManager()
    {
        return $this->entityManager;
    }

    /**
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function clearIndex()
    {
        $cmd = $this->getManager()->getClassMetadata('TVSISearchBundle:SearchIndex');
        $connection = $this->getManager()->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        }
        catch (\Exception $e) {
            $connection->rollback();
        }
    }

    /**
     * @param null/string $query
     * @return array
     */
    public function getResults($query = null)
    {
        $results = [];
        if (!$query) {
            return $results;
        }

        $queryBuilder = $this->getManager()
            ->createQueryBuilder()
            ->select('si')
            ->from('TVSISearchBundle:SearchIndex', 'si')
            ->where('si.title LIKE :query OR si.content LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('si.title', 'ASC')
            ->setFirstResult(0);

        $pages = $queryBuilder->getQuery()->getResult();
        foreach($pages as $page)
        {
            /** @var \TVSI\SearchBundle\Entity\SearchIndex $page*/
            $text = $page->getTitle();
            $keyword = array('title' => strtoupper($text),'file_contents' => $page->getContent(), 'path' => $page->getPath());
            $keyword['tokens'] = explode(' ', strtoupper($text));
            array_push($results, $keyword);
        }
        return $results;
    }

}