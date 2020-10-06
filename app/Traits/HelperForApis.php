<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait HelperForApis {

    /**
     * @param String|null $id
     * @param String $search
     * @return string
     */

    private function generateApiUrl(String $id = null, String $search = ""){

        $url = "https://www.googleapis.com/books/v1/volumes?projection=lite&key=".env('API_KEY');
        if(!is_null($id)){
            $url .= "&id=".$id;
        }else{
            $url .= "&q=".$search;
        }
        return $url;
    }

    public function getObjFromUrl(String $url) {
        $client = new Client();
        $stateQuery = $client->get($url);
        $response = $stateQuery->getBody();
        return json_decode($response, true);
    }

    public function searchApiReturnBooks(String $str, Int $limit){
        $apiResults =  $this->getObjFromUrl(
            $this->generateApiUrl(null, $str)
        );
        if($limit){
            return array_slice($apiResults['items'], 0, $limit);
        }
        return $apiResults['items'];
    }

}
