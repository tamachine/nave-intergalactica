<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\Faq;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index(): View
    {
        $this->authorize('content');      
        
        $data = [       
            'action' => collect([
                'route' => route('intranet.content.faq.create'),
                'title' => 'Create FAQ'
            ]),                
            'crumbs' => [
                'Content & SEO' => route('intranet.content.dashboard')
            ]
        ];

        return view('content.faq.index')->with($data);
    }  
    
    public function create(): View
    {
        $this->authorize('content');

        $data = [
            'action' => collect([
                'route' => route('intranet.content.faq.index'),
                'title' => "FAQ's"
            ]),
            'crumbs' => [
                'Settings' => route('intranet.settings'),
                "FAQ's" => route('intranet.content.faq.index')
            ],
        ];
        
        return view('content.faq.create')->with($data);
    }

    public function edit(Faq $faq, Request $request): View
    {
        $this->authorize('content');  

        $data = [      
            'action' => collect([
                'route' => route('intranet.content.faq.index', ['search' => $request->query('search')]),
                'title' => "FAQ's"
            ]),                 
            'crumbs' => [
                'Content & SEO' => route('intranet.content.dashboard'),
                "FAQ's"  => route('intranet.content.faq.index', ['search' => $request->query('search')])
            ],
            'faq' => $faq
        ];

        return view('content.faq.edit')->with($data);
    }
}
