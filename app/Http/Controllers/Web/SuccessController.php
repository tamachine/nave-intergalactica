<?php

namespace App\Http\Controllers\Web;

use Valitor;
use App\Jobs\CreateBookingPdf;
use App\Jobs\ConfirmCarenBooking;
use App\Models\Booking;

class SuccessController extends BaseController
{
    protected $booking;    

    public function index()
    {
        
        if (!checkSessionPayment()) {
            return redirect()->route('cars');
        }

        $this->setBooking();

        $this->setValitorResponseToBooking();

        if(!Valitor::checkResponse()) {
            return view('web.success.error');
        }

        $this->confirmBooking();

        dispatch(new CreateBookingPdf($this->booking, true)); //create and send the pdf to the client                

        return view('web.success.index');
    }

    protected function setBooking() {
        $bookingHashid = request()->session()->get('booking_data')['booking'];

        $this->booking = Booking::FindByHashid($bookingHashid);
    }

    protected function confirmBooking() {
       
        $this->booking->payment_status = 'confirmed';
        $this->booking->save();                
    }

    protected function setValitorResponseToBooking() {
       
        $this->booking->valitor_response = request()->all();
        $this->booking->save();                
    }   

    protected function footerImagePath(): string
    {
        return '/images/footer/success.png';
    }    
}
