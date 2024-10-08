<?php

namespace App\Http\Livewire\Admin\InsuranceFeature;

use App\Models\Feature as InsuranceFeature;
use App\Helpers\Language;
use Livewire\Component;

class Edit extends Component
{

    public $insuranceFeature;

    public $name;

    public $description;

    /*
    ***************************************************************
    ** METHODS
    ***************************************************************
    */   

    public function mount(InsuranceFeature $insuranceFeature)
    {
        $this->insuranceFeature = $insuranceFeature;
        
        foreach(Language::availableLanguages() as $key => $language) {
            $this->name[$key] = $this->insuranceFeature->getTranslation('name', $key);
            $this->description[$key] = $this->insuranceFeature->getTranslation('description', $key);
        }    
    }


    public function update()
    {
        $this->dispatchBrowserEvent('validationError');

        $this->validate(['name.'.Language::defaultCode() => 'required']);

        $this->insuranceFeature->update(['name' => $this->name, 'description' => $this->description]);        
        
        $this->dispatchBrowserEvent('open-success', ['message' => 'Insurance feature "' . $this->insuranceFeature->name .'" updated succesfully']);               
    }    
    
    public function delete()
    {
        
        $this->insuranceFeature->delete();

        session()->flash('status', 'success');
        session()->flash('message', __('The Insurance feature has been deleted'));

        return redirect()->route('intranet.insurance-feature.index');
        
    }

    public function render()
    {
        return view('livewire.admin.insurance-feature.edit');
    }
}
