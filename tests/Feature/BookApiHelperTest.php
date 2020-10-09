<?php

namespace Tests\Feature;

use App\Models\BookResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\BookApiHelper;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookApiHelperTest extends TestCase
{
    const TEST_ID = 'wHB_oIsXmOYC';
    const TEST_SEARCH = 'surf';
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetById()
    {
        $urlHit = 'https://www.googleapis.com/books/v1/volumes/'.self::TEST_ID;
        $book = $this->getBookResponseWithFakerData();
        $book->id = self::TEST_ID;
        Http::fake([
            // Stub a JSON response for google books endpoints...
            $urlHit => Http::response($this->getFakeApiBookResponse($book), 200)
        ]);
        $bookApiHelper = new BookApiHelper;
        $resultsForId = $bookApiHelper->getById(self::TEST_ID);
        Http::assertSent(function ($request) use ($urlHit) {
            return $request->url() == $urlHit;
        });
        $this->assertEquals($resultsForId, $book);
    }

    public function testSearch()
    {
        $urlHit = 'https://www.googleapis.com/books/v1/volumes?q='.self::TEST_SEARCH.'&projection=lite&key='.env('API_KEY');
        $book = $this->getBookResponseWithFakerData();
        $book2 = $this->getBookResponseWithFakerData();
        Http::fake([
            // Stub a JSON response for google books endpoints...
            $urlHit => Http::response($this->getFakeApiSearchResponse($book, $book2), 200)
        ]);
        $bookApiHelper = new BookApiHelper;
        $searchResults = $bookApiHelper->search(self::TEST_SEARCH);
        Http::assertSent(function ($request) use ($urlHit) {
            return $request->url() == $urlHit;
        });
        $this->assertEquals($searchResults, [$book,$book2]);
    }

    private function getFakeApiSearchResponse(BookResponse $book, BookResponse $book2){
        return [
            'items'=>[
                $this->getFakeApiBookResponse($book),
                $this->getFakeApiBookResponse($book2)
            ]
        ];
    }

    private function getFakeApiBookResponse(BookResponse $book){
        return [
            'id' => $book->id,
            'volumeInfo'=>[
                'title' => $book->title,
                'authors' => $book->authors,
                'description' => $book->description,
                'previewLink' => $book->link,
            ]
        ];
    }
}
