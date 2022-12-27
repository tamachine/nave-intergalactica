<?php

return [
    'url'       => env('CAREN_URL'),
    'apikey'    => env('CAREN_APIKEY'),
    'username'  => env('CAREN_USERNAME'),
    'password'  => env('CAREN_PASSWORD'),

    'endpoints' => [
        'login'             => 'vehicleapi/user/login',
        'vendor_list'       => 'vehicleapi/rental/list',
        'pickup_locations'  => 'vehicleapi/location/getpickuplist',
        'dropoff_locations' => 'vehicleapi/location/getdropofflist',
        'full_car_list'     => 'vehicleapi/class/getlist',
        'extra_list'        => 'vehicleapi/extra/getlist',
        'insurance_list'    => 'vehicleapi/insurance/getlist',
    ],

    'vendor_settings' => [
        'online_percentage' => 15,
        'caren_payment_option_id' => 598,
        'link_cms_caren' => false
    ],

    'main_data' => [
        'Total Price' => 'TotalPrice',
        'Online payment' => 'Balance',
        'Remaining balance' => 'PaymentsAmount',
        'Currency' => 'Currency',
        'Vehicle name' => 'Vehicle|Class',
        'Caren vehicle ID' => 'Vehicle|ClassId',
        'Caren reference ID ' => 'Id',
        'Caren reservation ID' => 'RentalReservationId',
        'Caren booking code' => 'BookingCode',
        'Caren guid' => 'Guid',
        'Pickup location' => 'Location|PickupName',
        'Pickup location Id' => 'Location|PickupId',
        'Dropoff location' => 'Location|DropoffName',
        'Dropoff location Id' => 'Location|DropoffId',
        'Pickup date' => 'DateFrom',
        'Dropoff date' => 'DateTo',
        'First name' => 'Customer|FirstName',
        'Last name' => 'Customer|LastName',
        'Email' => 'Customer|Email',
        'Passport' => 'Customer|Passport',
        'Address' => 'Customer|Address',
        'Country' => 'Customer|CountryName',
    ],

    'other_data' => [
        'Price' => 'Price',
        'Discount price' => 'DiscountPrice',
        'Additional discount price' => 'AdditionalDiscountPrice',
        'Confirmation price' => 'ConfirmationPrice',
        'Days' => 'Days',
        'Passangers' => 'Passangers',
        'Flight Number' => 'FlightNumber',
        'Arrival Time' => 'ArrivalTime',
        'Comments' => 'Comments',
        'Extras price' => 'ExtrasPrice',
        'Insurances price' => 'InsurancesPrice',
        'Status' => 'Status',
        'Status Text' => 'StatusText',
        'Coupon Code' => 'CouponCode',
        'Start Rental' => 'StartRental',
        'End Rental' => 'EndRental',
        'Editable' => 'Editable',
        'No follow-up mail' => 'NoFollowUpMail',
        'Checked in' => 'CheckedIn',
        'Terms & conditions accepted' => 'TermsAndConditionsAccepted',
        'Rental agreement signed' => 'RentalAgreementSigned',
        'Rental agreement signed URL' => 'RentalAgreementSignedUrl',
    ]
];
