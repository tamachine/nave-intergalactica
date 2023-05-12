<div class="flex items-center justify-between cursor-pointer">
    <h5 
        x-text="differentLocation ? '{!! __('car-search-bar.pick-up-location-title') !!}' : '{!! __('car-search-bar.title-location') !!}'"
        class="text-2xl text-left flex items-center">
    </h5>

    {{-- TOGGLE --}}
    <div x-on:click="toggleLocation()"
        id="toggle"
        class="bg-gray-primary rounded-full overflow-hidden  border-2 border-gray-primary shadow-[inset_0px_1px_2px_0px_rgba(0,0,0,0.25)] py-[4px] px-[5px]">
        <div class="relative flex align-stretch">
            {{-- Transition button --}}
            <span 
                {{-- :class="differentLocation ? 'left-auto right-0' : 'left-0'" --}}
                id="toggle-decoration"
                class="absolute left-0  w-1/2 h-full bg-black rounded-full transition-all">
            </span>

            {{-- Same location --}}
            <span 
                :class="differentLocation ? '' : 'active'" 
                id="same-location" class="toggle-selector">
                {!! __('car-search-bar.same-location') !!}
            </span>

            {{-- Different location --}}
            <span 
                :class="differentLocation ? 'active' : ''" 
                id="different-location" class="toggle-selector">
                {!! __('car-search-bar.different-location') !!}
            </span>
        </div>
    </div>
</div>


{{-- Variable de recogida y devoluvión --}}
@php 
    $moments = ['pickup', 'return']; 
@endphp


@foreach ($moments as $moment)
    @if ($moment == 'return') 
        <h5 :class="differentLocation ? '' : 'hidden'" class="text-2xl text-left flex items-center">
            {!! __('car-search-bar.return-location-title') !!}
        </h5>
    @endif

    <fieldset
        @if ($moment == 'return') id="locations-return" @endif
        :class="differentLocation ? 'different-location-true' : ''"
        class="flex gap-[4%] mt-4 mb-4 @if ($moment == 'return') hidden @endif">

        @foreach($locations as $key => $location)

            <div class="w-1/3">
                <input type="radio" id="{{$moment}}--{{ $key }}" name="location--{{$moment}}" value="{{ $location->name }}" class="hidden">
                <label for="{{$moment}}--{{ $key }}" class="location cursor-pointer">

                    <div class="location__image image-wrapper rounded-t-md overflow-hidden">
                        
                        {{-- IMPORTANTE: Revisar esto --}}
                        
                        @if ($location->getFeaturedImageModelImageInstance())
                            <x-image :model-image="$location->getFeaturedImageModelImageInstance()" class="w-full scale-105 transition-transform duration-700"/>
                        @else
                            {{-- <img class="w-full scale-105 transition-transform duration-700" src="{{ asset('images/locations/' . $item['image'] .'')}}" alt=""> --}}
                        @endif
                    </div>
                    
                    <div class="location__text bg-white px-5 py-4 rounded-b-md shadow-[0_1.5px_6px_0_rgba(0,0,0,0.1)] transition-[background] duration-700">
                        <p class="text-sm text-center font-sans-bold transition-[color] duration-700">
                            {{ $location->name }}
                        </p>
                    </div>
                    
                    @if ($location->pickup_input_info || $location->dropoff_input_info)
                        <small class="inline-block w-full text-gray-light text-xs text-center mt-3">
                            @if ($moment == 'return')
                                {{ $location->pickup_input_info }}
                            @else 
                                {{ $location->dropoff_input_info }}
                            @endif
                        </small>
                    @endif
                </label>
            </div>
        @endforeach

    </fieldset>
@endforeach


@push('scripts')
    <script>


        /********************
            PERSONALIZAR SELECTOR
        ********************/

        const radioButtonsPickup = document.querySelectorAll('input[name="location--pickup"]');
        const radioButtonsReturn = document.querySelectorAll('input[name="location--return"]');

        radioButtonsPickup.forEach((button) => {
            button.addEventListener('change', () => {
                // Reset selection
                const pickupInputs = document.querySelectorAll('input[name="location--pickup"]');
                pickupInputs.forEach(pickupInput => {
                    pickupInput.nextElementSibling.classList.remove('selected');
                });

                let selected = document.querySelector('input[name="location--pickup"]:checked');

                // Show value of selected on input
                let selectedValue = selected.value;
                const pickupInput = document.getElementById('pickup-location')
                pickupInput.value = selectedValue;

                // Si es mismo destino, que simule el click en el de abajo y rellene los dos inputs
                const locationsReturn = document.getElementById('locations-return');
                if (locationsReturn.classList.contains('different-location-true') === false) {
                    let selectedDifferentLocation = document.querySelector('input[name="location--return"][value="' + selectedValue + '"]');
                    selectedDifferentLocation.click();
                }

                // Show label as active
                let selectedId = selected.id;
                let selectedLabel = selected.nextElementSibling;
                
                pickupInput.parentElement.classList.add('active');
                selectedLabel.classList.add('selected');

                activeGroupInput();
            });
        });

        radioButtonsReturn.forEach((button) => {
            button.addEventListener('change', () => {
                // Reset selection
                const returnInputs = document.querySelectorAll('input[name="location--return"]');
                returnInputs.forEach(returnInput => {
                    returnInput.nextElementSibling.classList.remove('selected');
                });

                let selected = document.querySelector('input[name="location--return"]:checked');

                // Show value of selected on input
                let selectedValue = selected.value;
                const returnInput = document.getElementById('return-location')
                returnInput.value = selectedValue;
                
                // Show label as active
                let selectedId = selected.id;
                let selectedLabel = selected.nextElementSibling;
                
                returnInput.parentElement.classList.add('active');
                selectedLabel.classList.add('selected');

                activeGroupInput();
            });
        });

        
        const activeGroupInput = () => {
            const pickupInput = document.getElementById('pickup-location');
            const returnInput = document.getElementById('return-location');
            const setFilled = pickupInput.parentElement;

            if (pickupInput.value !== '' && returnInput.value !== '') {
                setFilled.parentElement.classList.add('active')
            }
        }


        // Poner recogida y devolución con el mismo valor
        const setSameLocationValue = () => {
            let selected = document.querySelector('input[name="location--pickup"]:checked');

            if (selected !== null) {
                let selectedValue = selected.value;
                
                let selectedDifferentLocation = document.querySelector('input[name="location--return"][value="' + selectedValue + '"]');
                selectedDifferentLocation.click();
            }
        }



        /********************
            ANIMACIÓN TOGGLE
        ********************/
        const toggle = document.getElementById('toggle');
        const toggleDecoration = document.getElementById('toggle-decoration');
        const sameLocation = document.getElementById('same-location');
        const differentLocation = document.getElementById('different-location');
    
        let sameLocationWidth;
        let sameLocationLeft;
        let differentLocationWidth;
        let differentLocationLeft;

        const getLocationsVariables = () => {
            sameLocationWidth = sameLocation.offsetWidth;
            differentLocationWidth = differentLocation.offsetWidth;

            toggleLeft = toggle.getBoundingClientRect().left;
            
            sameLocationTotalLeft = sameLocation.getBoundingClientRect().left;
            sameLocationLeft = sameLocationTotalLeft - toggleLeft - 7;

            differentLocationTotalLeft = differentLocation.getBoundingClientRect().left;
            differentLocationLeft = differentLocationTotalLeft - toggleLeft - 7;
        }

        const setSameLocationToggle = () => {
            toggleDecoration.style.width = sameLocationWidth + 'px';
            toggleDecoration.style.left = sameLocationLeft + 'px';
        }

        const setDifferentLocation = () => {
            toggleDecoration.style.width = differentLocationWidth + 'px';
            toggleDecoration.style.left = differentLocationLeft + 'px';
        }

        const startLocation = () => {
            getLocationsVariables()

            if(sameLocation.classList.contains('active') == true) {
                // Pone el selector en Mismo lugar
                setSameLocationToggle()
            } else {
                // Pone el selector en Lugares diferentes
                setDifferentLocation()
            }
        }

        const toggleLocation = () => {
            getLocationsVariables()

            if(sameLocation.classList.contains('active') == true) {
                // Pone el selector en Lugares diferentes
                setDifferentLocation()
            } else {
                // Pone el selector en Mismo lugar
                setSameLocationToggle()
                // Pone el input en el mismo lugar
                setSameLocationValue()
            }
        }
        
    </script>
@endpush