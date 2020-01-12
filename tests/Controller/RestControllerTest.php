<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpClient\HttpClient;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class RestControllerTest extends TestCase
{

    public function testGetConvertActionOk()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:80/api/convert?from=EUR&to=EUR&amount=10.00');

        $statusCode = $response->getStatusCode();
        self::assertEquals(200, $statusCode);

        self::assertEquals(10,  $response->getContent());
    }

    public function testGetConvertActionWrongFrom()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:80/api/convert?from=dsda&to=DKK&amount=10.00');

        $statusCode = $response->getStatusCode();
        self::assertEquals(500, $statusCode);
    }
}
