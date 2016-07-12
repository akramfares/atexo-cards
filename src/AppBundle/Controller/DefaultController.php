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
            // Récupération du client Guzzle
            $client = $this->get('guzzle.client.api_atexo');
            // Récupération des cartes
            $json = $client->request('GET', '/test/cards/57187b7c975adeb8520a283c')->getBody();
            $data = json_decode($json);
            // Ordonner les cartes
            $result = $this->sortCards($data);
            // Envoyer le résultat à l'API
            $response = $client->request('POST', '/test/'.$data->exerciceId, ['json' => $result]);
            $result["StatusCode"] = $response->getStatusCode();
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
        return new JsonResponse($result);
    }

    /*
     * Ordonner les cartes selon la catégorie et la valeur
     * @param $data: donnée JSON des cartes non triées
     * @return array: tableau trié des cartes
    */
    public function sortCards($data)
    {
        $categoryOrder = $data->data->categoryOrder;
        $valueOrder = $data->data->valueOrder;

        $cards = [];
        // Pour chaque carte non ordonnée
        foreach ($data->data->cards as $card) {
            // Récupérer l'ordre de la catégorie et de la valeur selon le tableau d'ordres
            $category = array_search($card->category, $categoryOrder);
            $value = array_search($card->value, $valueOrder);
            // Créer un tableau de 2 dimensions contenant la valeur et l'ordre de la carte
            // Les clés de ce tableau sont la catégorie et la valeur de la carte
            $cards[$category][$value] = ["category" => $card->category, "value" => $card->value];
        }
        // Ordonner le tableau récursivement selon les clés
        $this->ksortRecursive($cards);
        // Transformer le tableau en une seule dimension
        $cards = call_user_func_array("array_merge", $cards);

        $result["cards"] = $cards;
        $result["categoryOrder"] = $categoryOrder;
        $result["valueOrder"] = $valueOrder;

        return $result;
    }

    /*
     * Ordonner un tableau récursivement selon les clés
     * @param $array: tableau à trier
     * @return boolean
    */
    public function ksortRecursive(&$array) {
        if (!is_array($array))
        {
            return false;
        }
        ksort($array);
        foreach ($array as &$arr) {
            $this->ksortRecursive($arr);
        }
        return true;
    }
}
