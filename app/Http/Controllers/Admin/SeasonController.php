<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use Illuminate\View\View;

class SeasonController extends Controller
{
    public function index(): View
    {
        $this->authorize('admin');

        $data = [
            'action' => collect([
                'route' => route('intranet.season.create'),
                'title' => 'Create Season'
            ]),
            'crumbs' => [
                'Settings' => route('intranet.settings')
            ]
        ];

        return view('admin.season.index')->with($data);
    }

    public function create(): View
    {
        $this->authorize('admin');

        $data = [
            'action' => collect([
                'route' => route('intranet.season.index'),
                'title' => 'Seasons'
            ]),
            'crumbs' => [
                'Settings' => route('intranet.settings'),
                'Seasons' => route('intranet.season.index')
            ],
        ];

        return view('admin.season.create')->with($data);
    }

    public function edit($hashid): View
    {
        $this->authorize('admin');

        $season = Season::where('hashid', $hashid)->firstOrFail();

        $data = [
            'season' => $season,
            'seasonName' => $season->name,
            'action' => collect([
                'route' => route('intranet.season.index'),
                'title' => 'Seasons'
            ]),
            'crumbs' => [
                'Settings' => route('intranet.settings'),
                'Seasons' => route('intranet.season.index')
            ],
        ];

        return view('admin.season.edit')->with($data);
    }
}
