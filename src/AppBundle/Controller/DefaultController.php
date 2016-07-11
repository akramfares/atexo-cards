<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        try {
            $client = $this->get('guzzle.client.api_atexo');
            $json = $client->request('GET', '/test/cards/57187b7c975adeb8520a283c')->getBody();
            $result = $this->sortCards($json);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
        return new JsonResponse($result);
    }

    private function sortCards($json)
    {
        return [];
    }
}
