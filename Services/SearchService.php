<?php
/**
 * Created by PhpStorm.
 * User: srinivasankumar
 * Date: 25/01/16
 * Time: 11:28 PM
 */

namespace BeeCMS\SearchBundle\Services;


class SearchService
{
    /** @var  SearchInterface */
    private $searchService;

    /**
     * @param SearchInterface $search
     */
    public function __construct(SearchInterface $search)
    {
        $this->searchService = $search;
    }

    /**
     * @param null $query
     * @return array
     */
    public function getResults($query = null)
    {
        return $this->searchService->getResults($query);
    }

    /**
     * @return SearchInterface
     */
    public function getSearchService()
    {
        return $this->searchService;
    }

    /**
     * @param SearchInterface $searchService
     */
    public function setSearchService($searchService)
    {
        $this->searchService = $searchService;
    }

    public function index()
    {
        $this->getSearchService()->index();
    }

}