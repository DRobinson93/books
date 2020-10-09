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

        $url = "https://www.googleapis.com/books/v1/volumes";
        if(!is_null($id)){
            //https://www.googleapis.com/books/v1/volumes/zyTCAlFPjgYC?key=yourAPIKey
            $url .= "/".$id;
        }else{
            $url .= "?q=".$search;
            $url.="&projection=lite&key=".env('API_KEY');
        }
        return $url;
    }

    public function getObjFromUrl(String $url) {
        $client = new Client();
        $stateQuery = $client->get($url);
        $response = $stateQuery->getBody();
        return json_decode($response, true);
    }

    public function searchApiReturnBooks(String $str, Int $limit = 0){
        $apiResults =  $this->getObjFromUrl(
            $this->generateApiUrl(null, $str)
        );
        if($limit){
            return array_slice($apiResults['items'], 0, $limit);
        }
        return $apiResults['items'];
    }

    public function searchApiReturnBook(String $id){
        $apiResults =  $this->getObjFromUrl(
            $this->generateApiUrl($id)
        );
        return $this->getDataFromApiResult($apiResults);
    }

    public function getDataFromApiResult(Array $apiResults){
        $info = $apiResults['volumeInfo'];
        return [
            'id'=>$apiResults['id'],
            'title'=>$info['title'],
            'authors'=>$info['authors'] ?? [],
            'description'=>$info['description'] ?? '',
            'link' =>$info['previewLink']
        ];
    }

}
