<?php

namespace App\Http\Livewire\Admin\Vendor;

use App\Models\Vendor;
use Livewire\Component;

class EarlyBird extends Component
{
    /*
    ***************************************************************
    ** PROPERTIES
    ***************************************************************
    */

    /**
     * @var App\Models\Vendor
     */
    public $vendor;

    /**
     * @var array
     */
    public $early_bird = [];

    /*
    ***************************************************************
    ** METHODS
    ***************************************************************
    */

    public function mount(Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->early_bird = $this->vendor->early_bird;

        if (is_null($this->early_bird)) {
            $this->early_bird = [];

            for($i = 1; $i <= 10; $i++) {
                $this->early_bird[$i] = 0;
            }
        }
    }

    public function saveEarlyBird()
    {
        $this->dispatchBrowserEvent('validationError');

        $this->validate(
            [
                'early_bird.*' => ['required', 'numeric', 'gte:0', 'lt:100'],
            ],
            [
                'early_bird.*.required' => 'Discount is required',
                'early_bird.*.gte' => 'Discount must be a positive value',
                'early_bird.*.lt' => 'Discount must less than 100%!',
            ]
        );

        $this->vendor->update([
            'early_bird' => $this->early_bird
        ]);

        session()->flash('status', 'success');
        session()->flash('message', 'Early Bird settings updated for ' . $this->vendor->name);

        return redirect()->route('intranet.vendor.edit', ["vendor" => $this->vendor->hashid, "tab" => "early"]);
    }

    public function render()
    {
        return view('livewire.admin.vendor.early-bird');
    }
}
