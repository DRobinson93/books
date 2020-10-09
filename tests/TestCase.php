<?php

namespace Tests;

use App\Models\BookResponse;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getBookResponseWithFakerData(){
        $bookResponse = new BookResponse();
        $faker = Faker::create();
        $bookResponse->id = $faker->word();
        $bookResponse->title = $faker->sentence();
        $bookResponse->description = $faker->paragraph();
        $bookResponse->link = 'www.google.com';
        $bookResponse->authors = [$faker->word(), $faker->word()];
        return $bookResponse;
    }
}
