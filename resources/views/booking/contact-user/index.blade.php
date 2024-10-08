<x-admin-layout>
    <x-slot name="title">
        Contact Users
    </x-slot>

    <x-slot name="breadcrumbs">
        <x-admin.breadcrumbs :crumbs="$crumbs" />
    </x-slot>    

    <livewire:booking.contact-user.index />
</x-admin-layout>
