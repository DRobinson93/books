<?php
namespace App\Services;
use App\Interfaces\QueryBook;
use App\Models\BookResponse;
use Illuminate\Support\Facades\Http;

class BookApiHelper implements QueryBook
{
    const RESULT_LIMIT = 20;

    public function getById(String $id) : BookResponse
    {
        $apiResults = $this->getObjFromUrl(
            $this->generateApiUrl($id)
        );
        return $this->getBookFromApiResult($apiResults);
    }

    public function search(String $searchTxt) : Array
    {
        $results = $this->searchApi($searchTxt, self::RESULT_LIMIT);
        foreach($results as &$result){
            $result = $this->getBookFromApiResult($result);
        }
        return $results;
    }

    /**
     * @param String $url
     * @return mixed
     */
    private function getObjFromUrl(String $url)
    {
        $response = Http::get($url);
        return $response->json();
    }

    /**
     * @param String|null $id
     * @param String $search
     * @return string
     */

    private function generateApiUrl(String $id = null, String $search = "")
    {
        $url = "https://www.googleapis.com/books/v1/volumes";
        if (!is_null($id)) {
            //https://www.googleapis.com/books/v1/volumes/zyTCAlFPjgYC
            $url .= "/" . $id;
        } else {
            $url .= "?q=" . $search;
            $url .= "&projection=lite&key=" . env('API_KEY');
        }
        return $url;
    }

    /**
     * @param String $str
     * @param Int $limit
     * @return array|mixed
     */
    private function searchApi(String $str, Int $limit = 0)
    {
        $apiResults = $this->getObjFromUrl(
            $this->generateApiUrl(null, $str)
        );
        if ($limit) {
            return array_slice($apiResults['items'], 0, $limit);
        }
        return $apiResults['items'];
    }

    /**
     * @param array $apiResults
     * @return BookResponse
     */
    private function getBookFromApiResult(Array $apiResults) : BookResponse
    {
        $info = $apiResults['volumeInfo'];
        $book = new BookResponse();
        $book->id = $apiResults['id'];
        $book->title = $info['title'];
        $book->authors = $info['authors'] ?? [];
        $book->description = $info['description'] ?? '';
        $book->link = $info['previewLink'];
        return $book;
    }

}
