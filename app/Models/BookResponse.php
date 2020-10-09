<?php

namespace App\Models;
use Faker\Factory as Faker;

class BookResponse{
    public String $id, $title, $link, $description;
    public array $authors;
}
