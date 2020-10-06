<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Traits\HelperForApis;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    use HelperForApis;
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->count(rand(0,2))->create();
        $users->each(function(User $user) {
            //foreach user, enter in books from the google api
            $apiBooksData = $this->searchApiReturnBooks('surf', rand(0,10));//a word I know returns results
            foreach($apiBooksData as $index=> $apiBook){
                $book = Book::factory()->make([
                    'api_id' => $apiBook['id'],
                    'rank' => $index //this is just a sequential number so index works
                ]);
                $user->books()->save($book);
            }
        });
    }
}
