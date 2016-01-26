<?php
/**
 * Created by PhpStorm.
 * @author Srinivasan Kumar <srinikumar11@gmail.com>
 * Date: 22/01/16
 * Time: 6:06 PM
 */

namespace BeeCMS\SearchBundle\Services;


use Symfony\Component\Finder\Finder;

class FileSearchService implements SearchInterface
{
    /** @var array  */
    private $fileContents = array();

    /** @var null  */
    private $scanDirectory = null;

    /**
     *
     */
    public function __construct()
    {
        $this->index();
    }

    /**
     * @return string
     */
    public function getScanDirectory()
    {
        return $this->scanDirectory?$this->scanDirectory:__DIR__."/../Data/";
    }

    /**
     * @param $dir
     */
    public function setScanDirectory($dir)
    {
        $this->scanDirectory = $dir;
    }

    /**
     * {@inheritdoc}
     */
    public function index()
    {
        $finder = new Finder();
        $finder->files()->in($this->getScanDirectory());

        foreach ($finder as $file) {
            /**
             * @var \Symfony\Component\Finder\SplFileInfo $file
             */
            $this->fileContents[] = [
                'title' => $file->getRelativePathname(),
                'file_contents' => $file->getContents(),
                'path' => $file->getRealpath()
            ];
        }
    }


    /**
     * @param null/string $query
     * @return array
     */
    public function getResults($query = null)
    {
        $matchedResults = [];
        if (!$query) {
            return $matchedResults ;
        }
        foreach($this->getFileContents() as $result)
        {
            $result['tokens'] = explode(' ', strtoupper($result['title']));
            if(stripos($result['title'], $query) >= 0){
                array_push($matchedResults , $result);
                continue;
            } else if(stripos($result['file_contents'], $query) >= 0){
                array_push($matchedResults , $result);
                continue;
            }
        }
        return $matchedResults ;
    }

    /**
     * @return array
     */
    public function getFileContents()
    {
        return $this->fileContents;
    }

    /**
     * @param array $fileContents
     */
    public function setFileContents($fileContents)
    {
        $this->fileContents = $fileContents;
    }

}