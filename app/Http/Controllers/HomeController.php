<?php


namespace App\Http\Controllers;

use App\Models\Post;

class HomeController
{
    public function __invoke()
    {
        return view('pages.index');
    }
}
