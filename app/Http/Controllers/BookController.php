<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Traits\HelperForApis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\QueryBook;

class BookController extends Controller
{
    use HelperForApis;
    protected QueryBook $queryBook;
    public function __construct(QueryBook $queryBook)
    {
        $this->queryBook = $queryBook;
    }
    /**
     * return all book api ids for the auth user
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Auth::user()->books()->orderBy('rank')->get()->pluck('api_id')->toArray());
    }

    /**
     * truncate the current settings and insert the new ones.
     * this updates the order and accounts for add/delete all in one
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authUserId = Auth::user()->id;
        Book::where('user_id', $authUserId)->delete();
        foreach($request->apiIds as $rank => $apiId){
            $book = new Book([
                'api_id' =>$apiId,
                'rank' =>$rank,
                'user_id' =>$authUserId
            ]);
            $book->save();
        }
        return response(true);
    }

    /**
     * Display the specified resource.
     *
     * @param String $apiKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(String $apiKey)
    {
        return response()->json($this->queryBook->getById($apiKey));
    }

    /**
     * search for book
     *
     * @param String $searchTxt
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(String $searchTxt)
    {
        return response()->json($this->queryBook->search($searchTxt));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
    }
}
