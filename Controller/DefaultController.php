<?php

namespace BeeCMS\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $search_query = $request->get('query');

        /** @var array $results */
        $results = $this->get('beecms.search.service')->getResults($search_query);

        return $this->render('BeeCMSSearchBundle:Default:index.html.twig', array('results' => $results));
    }


    public function autoCompleteAction($query = null)
    {
        /** @var array $results */
        $results = $this->get('beecms.search.service')->getResults($query);

        return new JsonResponse($results);
    }

}
