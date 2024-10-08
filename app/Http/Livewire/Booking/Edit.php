<?php

namespace App\Http\Livewire\Booking;

use App\Apis\Caren\Api;
use App\Jobs\CreateBookingPdf;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Location;
use App\Notifications\SendBookingPdfMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App;
use App\Jobs\ConfirmCarenBooking;

class Edit extends Component
{
    /*
    ***************************************************************
    ** PROPERTIES
    ***************************************************************
    */

     /**
     * @var App\Models\Booking
     */
    public $booking;

    /**
     * @var string
     */
    public $order_id;

    /**
     * @var string
     */
    public $vendor_name;

    /**
     * @var string
     */
    public $booking_date;

    /**
     * @var string
     */
    public $car = "";

    /**
     * @var string
     */
    public $car_name = "";

     /**
     * @var array
     */
    public $cars = [];

    /**
     * @var string
     */
    public $pickup_location = "";

    /**
     * @var string
     */
    public $pickup_date;

    /**
     * @var string
     */
    public $pickup_hour = "12:00";

    /**
     * @var string
     */
    public $dropoff_location = "";

    /**
     * @var string
     */
    public $dropoff_date;

    /**
     * @var string
     */
    public $dropoff_hour = "12:00";

    /**
     * @var array
     */
    public $locations = [];

    /**
     * @var array
     */
    public $hours = [];

    /**
     * @var int
     */
    public $total_price;

    /**
     * @var int
     */
    public $online_payment;

    /**
     * @var string
     */
    public $payment_status;

    /**
     * @var string
     */
    public $vendor_status;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $cancel_reason;

    /**
     * @var string
     */
    public $comment;
    
    /*
    ***************************************************************
    ** METHODS
    ***************************************************************
    */

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->order_id = $booking->order_id;
        $this->vendor_name = $booking->vendor->name;
        $this->booking_date = $booking->created_at->format('d-m-Y H:i');

        $this->cars = $booking->vendor->cars()->orderBy('name', 'asc')->pluck('name', 'hashid');
        $this->car = $booking->car ? $booking->car->hashid : null;
        $this->car_name = $booking->car_name;

        $this->locations = $booking->vendor->locations()->orderBy('name', 'asc')->pluck('name', 'hashid');
        $this->pickup_location = $booking->pickupLocation->hashid;
        $this->dropoff_location = $booking->pickupLocation->hashid;

        $this->hours = hours_dropdown();
        $this->pickup_date = $booking->pickup_at->format('d-m-Y');
        $this->pickup_hour = $booking->pickup_at->format('H:i');
        $this->dropoff_date = $booking->dropoff_at->format('d-m-Y');
        $this->dropoff_hour = $booking->dropoff_at->format('H:i');

        $this->total_price = $booking->total_price;
        $this->online_payment = $booking->online_payment;

        $this->payment_status = $booking->payment_status;
        $this->vendor_status = $booking->vendor_status;
        $this->status = $booking->status;
        $this->cancel_reason = $booking->cancel_reason;        
    }

    public function getRemainingBalanceProperty()
    {
        return $this->total_price - $this->online_payment;
    }

    public function editBooking()
    {
        $this->dispatchBrowserEvent('validationError');

        $rules = [
            'car'               => ['required'],
            'pickup_location'   => ['required'],
            'dropoff_location'  => ['required'],
            'pickup_date'       => ['required', 'date'],
            'dropoff_date'      => ['required', 'date', 'after:pickup_date'],
            'total_price'       => ['required', 'numeric', 'gte:0'],
            'online_payment'    => ['required', 'numeric', 'gte:0', 'lte:total_price'],
            'cancel_reason'     => ['required_if:status,canceled'],
        ];

        $this->validate($rules);

        $car = Car::find(dehash($this->car));
        $pickup = Location::find(dehash($this->pickup_location));
        $dropoff = Location::find(dehash($this->dropoff_location));

        $this->booking->update([
            'car_id' => $car->id,
            'car_name' => $car->name,
            'pickup_at' => Carbon::createFromFormat("d-m-Y H:i", $this->pickup_date . " " . $this->pickup_hour),
            'pickup_location' => $pickup->id,
            'pickup_name' => $pickup->name,
            'dropoff_at' => Carbon::createFromFormat("d-m-Y H:i", $this->dropoff_date . " " . $this->dropoff_hour),
            'dropoff_location' => $dropoff->id,
            'dropoff_name' => $dropoff->name,
            'total_price' => $this->total_price,
            'online_payment' => $this->online_payment,
            'payment_status' => $this->payment_status,
            'vendor_status' => $this->vendor_status,
            'status' => $this->status,
            'cancel_reason' => $this->cancel_reason,
        ]);

        $this->dispatchBrowserEvent('showOverlay');

        $changes = $this->booking->getChanges();

        // If the online payment has changed and there is an affiliate, recalculate the commision
        if (in_array('online_payment', array_keys($changes)) && $this->booking->affiliate) {
            $this->booking->update([
                'affiliate_commission'  => round($this->online_payment * $this->booking->affiliate->commission_percentage / 100)
            ]);
        }

        if (count($changes)) {
            // Save a booking log if there have been changes
            $this->booking->logs()->create([
                'user_id'    => auth()->user()->id,
                'message'    => 'Booking information updated: ' . translate_log_fields($changes)
            ]);

            // Check if we are dealing with a Caren Booking
            if ($this->booking->caren_info) {
                $api = new Api();
                $carenBooking = $api->editBooking(array_merge(["Guid" => $this->booking->caren_guid], $this->booking->carenParameters));

                // When there is an error editing, "Success" is equal to false
                if (isset($carenBooking["Success"]) && $carenBooking["Success"] == false) {
                    Log::error("Error updating booking in Caren. Booking ID: " . $this->booking->id . ". Error: " . $carenBooking["Message"]);
                }

                // Check if the status has changed to "Confirmed"
                // If so, confirm the booking in Caren
                if (isset($changes['status']) && $this->status == 'confirmed') {
                    dispatch(new ConfirmCarenBooking($this->booking));
                }

                // Reload the booking information from Caren
                $bookingInfo = $api->bookingInfo([
                    "RentalId" => $this->booking->vendor->caren_settings["rental_id"],
                    "Guid" => $this->booking->caren_guid,
                ]);

                // When "Success" is set, there has been an error (irony)
                if (isset($bookingInfo["Success"])) {
                    Log::error("Error reloading Caren booking. Booking ID: " . $this->booking->id . ". Error: " . $bookingInfo["Message"]);
                    $this->dispatchBrowserEvent('hideOverlay');
                    return;
                }

                $this->booking->update([
                    'caren_info' => $bookingInfo
                ]);
            }
        }

        // Save another booking log if there is a comment
        if (!emptyOrNull($this->comment)) {
            $this->booking->logs()->create([
                'user_id'    => auth()->user()->id,
                'message'    => 'Comment added: ' . $this->comment
            ]);
        }

        session()->flash('status', 'success');
        session()->flash('message', 'Booking Information updated.');

        return redirect()->route('intranet.booking.edit', $this->booking->hashid);
    }

    public function createPdf()
    {
        $this->dispatchBrowserEvent('showOverlay');

        dispatch(new CreateBookingPdf($this->booking));

        session()->flash('status', 'success');
        session()->flash('message', 'Creating the booking PDF');

        return redirect()->route('intranet.booking.edit', $this->booking->hashid);
    }   

    public function sendPdf()
    {
        $this->booking->notify(new SendBookingPdfMail($this->booking));
        $this->dispatchBrowserEvent('open-success', ['message' => 'PDF sent to the customer']);
    }

    public function render()
    {
        return view('livewire.booking.edit');
    }
}
