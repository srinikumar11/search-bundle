<?php

namespace BeeCMS\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter;
use BeeCMS\SearchBundle\Entity\SearchIndex;

class ApiController extends Controller
{
    /**
     * @param null $query
     * @return JsonResponse
     */
    public function getQueryAction($query = null)
    {
        /** @var array $results */
        $results = $this->get('beecms.search.service')->getResults($query);

        return new JsonResponse($results);
    }


    /**
     * @return array
     *
     */
    public function getListAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository('BeeCMSSearchBundle:SearchIndex')->findAll();

        return array('users' => $users);
    }

    /**
     * @param SearchIndex $index
     * @return array
     *
     * ParamConverter("index", "BeeCMSSearchBundle:SearchIndex")
     */
    public function getDetailAction(SearchIndex $index)
    {
        return $index;
    }
}
