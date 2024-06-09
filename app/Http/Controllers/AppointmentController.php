<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Medium;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Mail\RegistrationEmail;
use App\Mail\UpdateEmail;
use App\Mail\DeleteEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return "HAHA";
        return view('appointments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('appointments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ($request->title == "Raju") {
            $request->merge(['title' => 'Appointment with Dr Raju']);
        } else {
            $request->merge(['title' => 'Appointment with Dr Kamala']);
        }

        // return $request;

        $booking = Appointment::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'booked_at' => $request->booking,
            'time' => $request->time,
            'status' => 'Booked'
        ]);
        
        return view('appointments.success', compact('booking'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        if ($user->getRoleNames()->implode("") == "admin" || $user->getRoleNames()->implode("") == "doctor") {
            $appointment = Appointment::findOrFail($id);
        } else {
            $appointment = Appointment::with('medicines')->where('user_id', auth()->id())->findOrFail($id);
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        $medicines = Medicine::simplePaginate(5);

        // return $medicines;
        if ($user->getRoleNames()->implode("") == "admin" || $user->getRoleNames()->implode("") == "doctor") {
            $appointment = Appointment::findOrFail($id);
        } else {
            $appointment = Appointment::where('user_id', auth()->id())->findOrFail($id);
        }
        return view('appointments.edit', compact('appointment','medicines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    $user = auth()->user();
    $appointment = Appointment::findOrFail($id);

    if ($request->title == "Raju") {
        $request->merge(['title' => 'Appointment with Dr Raju']);
    } else {
        $request->merge(['title' => 'Appointment with Dr Kamala']);
    }

    if ($user->getRoleNames()->implode("") == "doctor" || $user->getRoleNames()->implode("") == "admin") {
        $appointment->update([
            'remark'=>$request->remark,
            'fee'=>$request->fee,
            'status'=>$request->status
        ]);
        
        foreach ($request->medicine as $key => $amount) {
            if($amount > 0){
                $medicine = Medicine::findOrFail($key);
                // minus from medicine inventory
                $medicine->update(['amount'=>abs($medicine->amount - $amount)]);
                $appointment->medicines()->syncWithoutDetaching([$key => ['appointment_id' => $id,'medicine_id'=>$key, 'amount'=>$amount]]);
            }

        }
       
    }else{    
        $appointment->update([
            'title' => $request->title,
            'booked_at' => $request->booking,
            'time' => $request->time
        ]);
    }
    return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment =  Appointment::with('user')->findOrFail($id);
        Mail::to($appointment->user->email)->send(new DeleteEmail($appointment));
        $appointment->delete();
        return redirect()->back();
    }

    public function showPayment(string $id){
        $user = auth()->user();
        $medicines = Medicine::simplePaginate(5);

        // return $medicines;
        if ($user->getRoleNames()->implode("") == "admin" || $user->getRoleNames()->implode("") == "doctor") {
            $appointment = Appointment::findOrFail($id);
        } else {
            $appointment = Appointment::where('user_id', auth()->id())->findOrFail($id);
        }
        return view('appointments.edit', compact('appointment','medicines'));
    }

    public function payment(Request $request, string $id)
    {
    //    return $request->fee;
        $payment_data = array(
            'userSecretKey'=>env('TOYYIBPAY_SECRET'),
            'categoryCode'=>env('TOYYIBPAY_CATEGORY'),
            'billName'=>'Clinic System Payment',
            'billDescription'=>'Thank You for Your visit !',
            'billPriceSetting'=>1,
            'billPayorInfo'=>0,
            'billAmount'=>$request->fee * 100,
            'billReturnUrl'=>route('appointments.invoice',$id),
            'billCallbackUrl'=>'',
            'billExternalReferenceNo' => '',
            'billTo'=>'',
            'billEmail'=>'',
            'billPhone'=>'',
            'billSplitPayment'=>0,
            'billSplitPaymentArgs'=>'',
            'billPaymentChannel'=>'0',
            'billContentEmail'=>'',
            'billChargeToCustomer'=>1,
            'billExpiryDate'=>'',
            'billExpiryDays'=>3
          );  
        
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_POST, 1);
          curl_setopt($curl, CURLOPT_URL, 'https://dev.toyyibpay.com/index.php/api/createBill');
        //   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $payment_data);
        
          $result = curl_exec($curl);
          $info = curl_getinfo($curl);  
          curl_close($curl);
          $obj = json_decode($result);
          return redirect("https://dev.toyyibpay.com/".$obj[0]->BillCode);
        
    }

    public function processInvoice(Request $request, string $id){
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $date = date('Y-m-d h:i:s', time());
        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'paid_at'=> $date,
            'transaction_detail'=>$request->query(),
            'status'=>'Paid'
        ]);

        return redirect()->route('dashboard');
}
}
