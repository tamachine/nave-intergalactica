<div class="shadow overflow-hidden bg-white border-b border-gray-200 sm:rounded-lg p-10">
    <div class="text-xl text-green-700 font-bold">
        Bookings per month
    </div>

    <div id="bookings_month" style="height:500px;"></div>
</div>

<div class="mt-4 shadow overflow-hidden bg-white border-b border-gray-200 sm:rounded-lg p-10">
    <div class="text-xl text-green-700 font-bold">
        Bookings per location
    </div>

    <div class="mt-8 shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Location name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Bookings
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Average price
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Average days
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($locationBookings as $index => $locationBooking)
                    <tr class="{{$index % 2 == 0 ? 'bg-white' : 'bg-gray-50'}}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            {{ $locationBooking->pickup_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $locationBooking->number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ formatPrice($locationBooking->average_price, "ISK") }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ round($locationBooking->average_days, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
