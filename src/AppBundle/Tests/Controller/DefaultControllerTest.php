<?php

namespace AppBundle\Tests\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
    	// Exemple de cartes non ordonnées
    	$json = '{"exerciceId":"5782cc03975adeb8520a3ade","dateCreation":1468189699577,"candidate":{"candidateId":"57187b7c975adeb8520a283c","firstName":"Othmane","lastName":"QABLAOUI"},"data":{"cards":[{"category":"CLUB","value":"KING"},{"category":"CLUB","value":"TWO"},{"category":"HEART","value":"FOUR"},{"category":"SPADE","value":"SEVEN"},{"category":"CLUB","value":"EIGHT"},{"category":"HEART","value":"TWO"},{"category":"CLUB","value":"THREE"},{"category":"DIAMOND","value":"NINE"},{"category":"HEART","value":"KING"},{"category":"SPADE","value":"TEN"}],"categoryOrder":["DIAMOND","HEART","SPADE","CLUB"],"valueOrder":["ACE","TWO","THREE","FOUR","FIVE","SIX","SEVEN","EIGHT","NINE","TEN","JACK","QUEEN","KING"]},"name":"cards"}';
        
        // Création du mock du client Guzzle
        $mock = new MockHandler([
		    new Response(200, ['Content-Type' => 'application/json'] ,$json),
		]);
		$handler = HandlerStack::create($mock);
		$mockGuzzle = new Client(['handler' => $handler]);

		// Création du client symfony pour faire le test fonctionnel
		$client = static::createClient();
		// Mock du service Guzzle
		$client->getContainer()->set('guzzle.client.api_atexo', $mockGuzzle);

		// Appel au controller
        $crawler = $client->request('GET', '/');

        // Vérifier le status HTTP de la réponse
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // Vérifier le type de contenu retourné
        $this->assertTrue(
	    $client->getResponse()->headers->contains(
		        'Content-Type',
		        'application/json'
		    ),
		    'the "Content-Type" header is "application/json"'
		);
        // Vérifier si les cartes sont bien ordonnées
		$this->assertContains('{"cards":[{"category":"DIAMOND","value":"NINE"},{"category":"HEART","value":"TWO"},{"category":"HEART","value":"FOUR"},{"category":"HEART","value":"KING"},{"category":"SPADE","value":"SEVEN"},{"category":"SPADE","value":"TEN"},{"category":"CLUB","value":"TWO"},{"category":"CLUB","value":"THREE"},{"category":"CLUB","value":"EIGHT"},{"category":"CLUB","value":"KING"}],"categoryOrder":["DIAMOND","HEART","SPADE","CLUB"],"valueOrder":["ACE","TWO","THREE","FOUR","FIVE","SIX","SEVEN","EIGHT","NINE","TEN","JACK","QUEEN","KING"]}'
			, $client->getResponse()->getContent()
		);
    }
}
