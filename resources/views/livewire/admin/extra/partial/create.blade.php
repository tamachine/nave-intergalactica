<x-admin.form-section submit="saveExtra">
    <x-slot name="title">
        {{ $formTitle }}
    </x-slot>

    <x-slot name="description">
        {{ $formDescription }}
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="px-4 mt-4 md:mt-0">
            <x-admin.label for="name" value="{{ __('Name') }}" />
            <x-admin.input id="name" type="text" class="mt-1 block" maxlength="255" wire:model.defer="name" autocomplete="name" />
            <x-admin.input-error for="name" class="mt-2" />
        </div>

        <!-- Vendor -->
        <div class="px-4 mt-4 md:mt-0">
            <x-admin.label for="vendor" value="{{ __('Vendor') }}" />
            <select id="vendor" name="vendor" wire:model="vendor"
                class="disable-arrow block w-auto h-10 mt-1 pt-2 pl-3 pr-10 text-left border-gray-300 rounded-md"
            >
                <option value="">Select Vendor</option>
                @foreach ($vendors as $id => $name)
                    <option value="{{$id}}">{{ $name }}</option>
                @endforeach
            </select>
            <x-admin.input-error for="vendor" class="mt-2" />
        </div>

        @if (config('settings.booking_enabled.caren'))
            <!-- Caren Extra -->
            <div class="px-4 mt-4 md:mt-0">
                <x-admin.label for="caren_extra" value="{{ __('Caren Extra') }}" />
                <select id="caren_extra" name="caren_extra" wire:model="caren_extra"
                    class="disable-arrow block w-full h-10 mt-1 pt-2 px-3 text-left border-gray-300 rounded-md"
                >
                    <option value="">Select Extra</option>
                    @foreach ($caren_extras as $id => $name)
                        <option value="{{$id}}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-admin.button wire:loading.attr="disabled">
            {{ $formButton }}
        </x-admin.button>
    </x-slot>
</x-admin.form-section>
