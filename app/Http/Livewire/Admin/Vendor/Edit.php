<?php

namespace App\Http\Livewire\Admin\Vendor;

use App\Models\CarenVendor;
use App\Models\Vendor;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    /*
    ***************************************************************
    ** PROPERTIES
    ***************************************************************
    */

    /**
     * @var bool
     */
    public $edit = true;

    /**
     * @var App\Models\Vendor
     */
    public $vendor;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $vendor_code;

    /**
     * @var int
     */
    public $service_fee;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $brand_color;
    
    /*
    ***************************************************************
    ** METHODS
    ***************************************************************
    */

    public function mount(Vendor $vendor)
    {
        $this->vendor = $vendor;

        $this->name = $this->vendor->name;
        $this->vendor_code = $this->vendor->vendor_code;
        $this->service_fee = $this->vendor->service_fee;
        $this->status = $this->vendor->status;
        $this->brand_color = $this->vendor->brand_color;        
    }

    public function saveVendor()
    {
        $this->dispatchBrowserEvent('validationError');

        $this->validate([
            'name' => ['required', 'unique:vendors,name,' . $this->vendor->id],
            'vendor_code' => ['required', 'unique:vendors,vendor_code,' . $this->vendor->id],
            'service_fee' => ['required', 'numeric', 'gte:0'],
            'status' => ['required', 'in:active,hidden'],
            'brand_color' => ['required'],
        ]);

        $this->vendor->update([
            'name' => $this->name,
            'vendor_code' => $this->vendor_code,
            'service_fee' => $this->service_fee,
            'status' => $this->status,
            'brand_color' => $this->brand_color,
        ]);

        session()->flash('status', 'success');
        session()->flash('message', 'Vendor "' . $this->name .'" edited');

        return redirect()->route('intranet.vendor.edit', $this->vendor->hashid);
    }    

    public function deleteVendor()
    {
        // Check if any Caren vendors are linked to the vendor
        CarenVendor::where('vendor_id', $this->vendor->id)->update([
            'vendor_id' => null
        ]);

        $this->vendor->delete();

        session()->flash('status', 'success');
        session()->flash('message', __('The vendor has been deleted'));

        return redirect()->route('intranet.vendor.index');
    }

    public function render()
    {
        return view('livewire.admin.vendor.edit');
    }
}
