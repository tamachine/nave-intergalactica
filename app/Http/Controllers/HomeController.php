<?php

namespace App\Http\Controllers;

use App\Models\Landlord\Tenant;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'tenants' => Tenant::all()
        ]);
    }
}
