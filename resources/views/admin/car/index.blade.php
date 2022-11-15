<x-admin-layout>
    <x-slot name="title">
        {{ __('Cars') }}
    </x-slot>

    <x-slot name="action">
        <x-admin.action route="{{$action->get('route')}}" title="{{$action->get('title')}}" />
    </x-slot>

    <livewire:admin.car.index />
</x-admin-layout>
