<?php
namespace StatusPage\SDK;

use Guzzle\Http\Client as GuzzleClient;

class Client 
{
    private $pageId;
    private $guzzleClient;
    private $secretKey;

    public function __construct(GuzzleClient $guzzleClient, $pageId, $secretKey)
    {
        $this->guzzleClient = $guzzleClient;
        $this->guzzleClient->setBaseUrl('https://api.statuspage.io/v1');
        $this->guzzleClient->setDefaultOption('headers', array('Authorization' => 'OAuth '.$secretKey));

        $this->pageId = $pageId;
        $this->secretKey = $secretKey;

    }

    public function send($endpoint, $method = 'GET', $headers = null, $body = null)
    {
        if (null !== $body) {
            $body = json_encode($body);
        }

        $request = $this->guzzleClient->createRequest($method, $endpoint, $headers, $body);

        if (in_array($method, array('PUT', 'POST', 'DELETE'))) {
            $request->setHeader('content-type', 'application/json');
        }

        $response = $request->send();

        return json_decode($response->getBody(), true);
    }
}