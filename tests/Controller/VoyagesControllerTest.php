<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;



class VoyagesControllerTest extends WebTestCase {
    
    public function testAccesPage() {
    $client = static::createClient();
    $client->request('GET', '/voyages');
    $this->assertResponseStatusCodeSame(Response::HTTP_OK);
}

public function testContenmPage() {
    $client = static::createClient();
    $crawler = $client->request('GET', '/voyages');
    $this->assertSelectorTextContains('h1', 'Mes voyages');
    $this->assertSelectorTextContains('th', 'Ville');
    $this->assertCount(4, $crawler->filter('th'));
    $this->assertSelectorTextContains('h5', 'Seoul');
}

public function testLinkVille(){
    $client = static::createClient();
    $client->request('GET', '/voyages');
    
    // Clic sur un lien (le nom d'une ville)
    $client->clickLink('Seoul');
    
    // Récupération du résultat du clic
    $response = $client->getResponse();
    
    // Contrôle si le lien existe
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    
    // Récupération de la route et contrôle qu'elle est correcte
    $uri = $client->getRequest()->server->get("REQUEST_URI");
    $this->assertEquals('/voyages/voyage/101', $uri);
}




    
    
   
}

