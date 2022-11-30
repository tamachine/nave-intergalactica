<x-admin-layout>
    <x-slot name="title">
        {{ __('Create Extra') }}
    </x-slot>

    <x-slot name="backTo">
        <x-admin.back-to route="{{$action->get('route')}}" title="{{$action->get('title')}}" />
    </x-slot>

    <div class="space-y-6 p-10">
        <livewire:admin.extra.create :caren_extra="$caren_extra" />
    </div>
</x-admin-layout>
