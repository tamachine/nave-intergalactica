<?php

namespace App\Http\Controllers\Web;

class BlogSearchStringController extends BaseController
{
    public function index() {        
        return view('web.blog.search.string');   
    }   

    protected function footerImagePath(): string
    {
        return '/images/footer/blog.png';
    }   
}
