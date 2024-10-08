<?php

namespace App\Http\Livewire\Admin\Car;

use App\Models\Car;
use App\Models\Extra;
use Livewire\Component;

class Extras extends Component
{
    /*
    ***************************************************************
    ** PROPERTIES
    ***************************************************************
    */

    /**
     * @var App\Models\Car
     */
    public $car;

    /**
     * @var array
     */
    public $currentExtras = [];

    /**
     * @var array
     */
    public $availableExtras = [];

    /*
    ***************************************************************
    ** METHODS
    ***************************************************************
    */

    public function mount(Car $car, Extra $extra)
    {
        $this->car = $car;        
        $currentExtras = [];

        // Load the current extras
        foreach ($car->extras()->orderBy('order_appearance')->get() as $extra) {

            $this->currentExtras[] = [
                'id'    => $extra->hashid,
                'name'  => $extra->name,
                'color' => $extra->load('insurance')->insurance->color ?? '',
            ];

            $currentExtras[] = $extra->id;
        }

        // Load the available extras        
        foreach ($car->availableExtras()->get() as $extra) {
            if (!in_array($extra->id, $currentExtras)) {
                $this->availableExtras[] = [
                    'id'        => $extra->hashid,
                    'name'      => $extra->name,
                    'selected'  => false,
                    'color'     => $extra->is_insurance ? $extra->insurance->color : '',
                ];
            }
        }
    }

    public function addExtras()
    {
        foreach($this->availableExtras as $extra) {
            if ($extra["selected"]) {
                $this->car->extras()->attach(dehash($extra["id"]));
            }
        }

        session()->flash('status', 'success');
        session()->flash('message', 'Extras added to ' . $this->car->name);

        return redirect()->route('intranet.car.edit', ["car" => $this->car->hashid, "tab" => "extras"]);
    }

    public function deleteExtra($id)
    {
        $this->car->extras()->detach(dehash($id));

        session()->flash('status', 'success');
        session()->flash('message', 'Deleted extra from ' . $this->car->name);

        return redirect()->route('intranet.car.edit', ["car" => $this->car->hashid, "tab" => "extras"]);
    }

    public function render()
    {
        return view('livewire.admin.car.extras');
    }
}
