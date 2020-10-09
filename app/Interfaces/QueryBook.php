<?php
namespace App\Interfaces;

use App\Models\BookResponse;

interface QueryBook
{
    /**
     * @param String $id
     * @return BookResponse
     */
    public function getById(String $id) : BookResponse;

    /**
     * @param String $searchTxt
     * @return Array of BookResponse
     */
    public function search(String $searchTxt) : Array;
}
