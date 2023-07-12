<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Feature;

class InsurancesTable extends Component
{
    public $insurances;

    public $features;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Collection $insurances)
    {
        $this->insurances = $insurances;

        $this->features   = Feature::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.insurances-table');
    }
}
