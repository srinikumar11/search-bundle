<?php
/**
 * Created by PhpStorm.
 * User: srinivasankumar
 * Date: 25/01/16
 * Time: 10:21 PM
 */

namespace BeeCMS\SearchBundle\Services;


interface SearchInterface
{

    /**
     * @param null/string $query
     * @return array
     */
    public function getResults($query);


    /**
     * @return mixed
     */
    public function index();

}