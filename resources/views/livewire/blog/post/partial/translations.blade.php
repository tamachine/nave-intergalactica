<x-admin.form-section submit="saveTranslations" formClass="block">
    <x-slot name="title">
        {{ $formTitle }}
    </x-slot>

    <x-slot name="description">
        {{ $formDescription }}
    </x-slot>

    <x-slot name="form">
        @foreach(App\Helpers\Language::availableLanguages() as $key => $language)
            <!-- Title -->
            <div class="px-4 mt-4">
                <x-admin.label for="title_{{$key}}" value="{{ 'Title' }} - {{$language}}" />
                <x-admin.input id="title_{{$key}}" type="text" class="w-full mt-1 block" maxlength="255" wire:model.defer="titles.{{ $key }}" autocomplete="post_title" />
            </div>

            <!-- Slug -->
            <div class="px-4 mt-4">
                <x-admin.label for="slug_{{$key}}" value="{{ 'Slug' }} - {{$language}}" />
                <x-admin.input id="slug_{{$key}}" type="text" class="w-full mt-1 block" maxlength="255" wire:model.defer="slugs.{{ $key }}" autocomplete="post_slug" />
            </div>

            <!-- Summary -->
            <div class="px-4 mt-4">
                <x-admin.label for="summary_{{$key}}" value="{{ 'Summary' }} - {{$language}}" />
                <textarea id="summary_{{$key}}" class="mt-1 focus:ring-purple-700 focus:purple-700 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md placeholder-gray-400"
                    wire:model.defer="summaries.{{ $key }}" rows="3" autocomplete="post_summary">
                </textarea>
                <x-admin.input-error for="summaries.{{ $key }}" class="mt-2" />
            </div>

            <!-- Content -->
            <div class="px-4 mt-4">
                <x-admin.label for="content_{{$key}}" value="{{ __('Content') }} - {{$language}}" />
                <x-admin.tinymce-editor id="content_{{$key}}" wire:model.defer="contents.{{ $key }}" rows="3" autocomplete="post_content" height="400px" />
            </div>

            <hr class="my-8 px-4">
        @endforeach

    </x-slot>

    <x-slot name="actions">
        <x-admin.button wire:loading.attr="disabled">
            {{ $formButton }}
        </x-admin.button>
    </x-slot>
</x-admin.form-section>
