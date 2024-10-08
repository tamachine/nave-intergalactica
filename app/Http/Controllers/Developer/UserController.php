<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('developer');

        $data = [
            'action' => collect([
                'route' => route('intranet.developer.user.create'),
                'title' => 'Create developer user'
            ]),
        ];

        return view('developer.user.index')->with($data);
    }

    public function create(): View
    {
        $this->authorize('developer');

        $data = [
            'action' => collect([
                'route' => route('intranet.developer.user.index'),
                'title' => 'Developer users'
            ]),
            'crumbs' => [
                'Developer users' => route('intranet.user.index')
            ],
        ];

        return view('developer.user.create')->with($data);
    }
}
