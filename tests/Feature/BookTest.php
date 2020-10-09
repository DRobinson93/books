<?php

namespace Tests\Feature;

use App\Interfaces\QueryBook;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use phpDocumentor\Reflection\Types\Integer;
use Tests\TestCase;

class BookTest extends TestCase
{
    use DatabaseTransactions; //avoid changes to db on test

    protected $user1, $user2;
    const TEST_ID = 'E72-83oiZNcC';
    public function setUp(): void
    {
        parent::setUp();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
    }

    /**
     * make sure the correct results are in the index return.
     * no other user books should show
     *
     * @return void
     */
    public function testOnlyAuthUsersBooksReturn()
    {
        //add books for auth and non auth user
        $books1 = Book::factory(2)->make();
        $this->user1->books()->saveMany($books1);

        $books2 = Book::factory(2)->make();
        $this->user2->books()->saveMany($books2);

        $response = $this->actingAs($this->user1)->get('/book');
        $response->assertOk();

        $responseArr = $response->getOriginalContent();
        $this->assertTrue(is_array($responseArr));
        //book1 should exist in arr
        foreach($books1 as $book){
            $this->assertTrue(in_array($book->api_id, $responseArr));
        }
        //books 2 should not exist
        foreach($books2 as $book){
            $this->assertTrue(!in_array($book->api_id, $responseArr));
        }
    }

    /**
     * make sure the correct results are in the index return.
     * no other user books should show
     *
     * @return void
     */
    public function testShow()
    {
        $this->instance(QueryBook::class, Mockery::mock(QueryBook::class, function ($mock) {

            $book = $this->getBookResponseWithFakerData();
            $mock->shouldReceive('getById')->once()->andReturn($book);
        }));

        $response = $this->actingAs($this->user1)->get('/book/'.self::TEST_ID);
        $response->assertOk();
    }

    public function testSearch()
    {
        $this->instance(QueryBook::class, Mockery::mock(QueryBook::class, function ($mock) {
            $book = $this->getBookResponseWithFakerData();
            $book2 = $this->getBookResponseWithFakerData();
            $mock->shouldReceive('search')->once()->andReturn([$book, $book2]);
        }));

        $response = $this->actingAs($this->user1)->get('/book/search/blah');
        $response->assertOk();
    }

    public function testAdd()
    {
        $this->testStoreSetup();

        $book = Book::factory()->make();
        $response = $this->getResponseFromBookStore([$book->api_id]);
        $response->assertOk();
        $this->assertBookCountEquals(1);
    }

    private function getResponseFromBookStore(array $apiIds){
        return $this->actingAs($this->user1)->post('/book', ['apiIds'=>$apiIds]);
    }

    private function getBooksForUser1() {
        return Book::where('user_id', $this->user1->id);
    }

    private function assertBookCountEquals(int $num) {
        $this->assertEquals($this->getBooksForUser1()->count(),$num);
    }

    public function testDelete()
    {
        $this->testStoreSetup();
        //create one book
        $book = Book::factory()->make();
        $this->user1->books()->save($book);
        //verify count increased
        $this->assertBookCountEquals(1);

        //send empty array to wipe books
        $response = $this->getResponseFromBookStore([]);
        $response->assertOk();
        //verify count is at 0
        $this->assertBookCountEquals(0);
    }

    private function testStoreSetup(){
        //remove all records for test user
        $this->getBooksForUser1()->delete();
        //check count is none for this user
        $this->assertBookCountEquals(0);
    }

    public function testUpdateOrder()
    {
        $numOfBooksToInsert = 4;
        $this->testStoreSetup();
        //create # books
        $book = Book::factory($numOfBooksToInsert)->make();
        $this->user1->books()->saveMany($book);
        $this->assertBookCountEquals($numOfBooksToInsert);
        $apiIdsInOrder = $this->getUserApiKeysInOrder();
        $apiIdsInReverse = array_reverse($apiIdsInOrder);
        $response = $this->getResponseFromBookStore($apiIdsInReverse);
        $response->assertOk();
        $this->assertBookCountEquals($numOfBooksToInsert);
        $this->assertEquals($apiIdsInReverse, $this->getUserApiKeysInOrder());
    }

    private function getUserApiKeysInOrder(){
        return $this->user1->books()->orderBy('rank')->get()->pluck('api_id')->toArray();
    }
}
