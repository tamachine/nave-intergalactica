<div
    x-data="extraPopup()"
    >
    <div 
        class="flex justify-center gap-[60px]"
        x-data="{ showSummary : '{{ $showSummary}}' }"
    >        
        <div
            x-cloak 
            x-show="!showSummary"
            class="w-full flex flex-col gap-3 max-w-[780px]"
        >
            @foreach($extras as $extra)
                <x-extra.extra :extra=$extra />
            @endforeach

            <x-wire-spinner />

            <div
            wire:loading.remove
            class="md:mx-auto my-8 grid {{ $showMoreButton ? 'grid-cols-2' : 'grid-cols-1' }}  md:flex gap-3 font-sans-medium md:text-xl text-lg">
                @if($showMoreButton)
                    <button
                        wire:click="more"
                        class="
                            rounded-lg border border-black
                            px-3 py-4 md:px-[100px]
                            "
                        >
                    {{ __('extras.more') }}
                    </button>
                @endif
                    <button
                        class="
                            md:hidden
                            rounded-lg bg-pink-red text-white
                            px-3 py-4
                            "                        
                        x-on:click="window.location.href='{{ route('extras', ['car_hashid' => $car, 'showSummary' => true]) }}'"
                            >
                        {{ __('extras.continue') }}
                        
                    </button>
            </div>
        </div>

        <div 
            x-cloak
            :class="showSummary ? '' : 'md-max:hidden'">
            @include('web.summary.index', ['buttonText' => __('summary.continue')])
        </div>
    </div>

    @include('web.extras.partial.popup')
</div>
