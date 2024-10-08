<?php

namespace App\Http\Livewire\Common;

use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * This component uploads an image in a model. The model MUST use the HasUploadImages trait.
 * It shows only an input button. In order to show the model's image gallery, ImageGallery must be used.
 */
class ImageUpload extends Component
{
    use WithFileUploads;

    /** The image
     * @var object
     */
    public $image;

    /** Model where the image have to be uploaded. 
     *  The model MUST use the HasUploadImages trait
     * @var object
     */
    public $model;        

    /**
     * Uploads the image
     */
    public function updatedImage()
    {       

        $this->validate(['image' => 'file|max:50000']); //50mb

        $this->model->uploadImage($this->image, $this->imageName());     
        
        $this->emit('imageUploaded'); //listener to refresh the ImageGallery
    }

    public function render()
    {
        return view('livewire.common.image-upload');
    }

    protected function imageName() {
        return pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME);        
    }
}
