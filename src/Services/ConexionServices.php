<?php

namespace Desoft\Services;

use Exception;
use GuzzleHttp\Client;

class ConexionServices {

    private $senderObj;

    public function __construct()
    {
        $this->senderObj = new Client();
    }

    public function send($url, $payload, $method = 'POST')
    {
        try{

            $response = $this->senderObj->request($method, $url, [
                'query' => $payload
            ]);

            return $response;
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

}
