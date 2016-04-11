<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Model;

use Zend\Http\Client;

class Themeforest
{
    private $AccountName;
    private $ApiKey;


    public function __construct($AccountName, $ApiKey) {
        $this->AccountName = $AccountName;
        $this->ApiKey = $ApiKey;
    }


    //https://api.envato.com/v1/discovery/search/search/item?username=<account name>
    public function getItems () {
        $requestUrl = 'https://api.envato.com/v1/discovery/search/search/item?sort_by=date&username=' . $this->AccountName;

        $client = new Client($requestUrl, array(
            'maxredirects' => 0,
            'timeout'      => 30
        ));

        $headers = $client->getRequest()->getHeaders();
        $headers->addHeaderLine('Authorization', 'Bearer '.$this->ApiKey);

        $response = $client->send();

        return \Zend\Json\Json::decode($response->getContent(),true);
    }

}