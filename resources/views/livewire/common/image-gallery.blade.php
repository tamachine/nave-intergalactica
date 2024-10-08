<div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-2">    
    @foreach($images as $key => $image)               
        <livewire:common.image-card :model-image="$image" delete-listener="deleteGalleryImage" :delete-listener-param="$image->id" :wire:key="$key.now()" :hiddeAlts="$hiddeImageCardAlts"/>
    @endforeach
</div>
