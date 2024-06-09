<!DOCTYPE html>
<html lang="en">
@include('templates.head')

<body>
    @include('templates.navbar')

    <main class="container">

        <h1 class="mt-4">
            @if ($appointment->status=="Paid")
            Invoice Detail
            @else
            Appointment Details
            @endif
        </h1>

        <div class="card mt-4 m-auto" style="max-width:500px; min-width: 400px;">
            <div class="card-header fw-bold">
                {{ $appointment->title }}
            </div>
            <div class="card-body">
                <div>
                    <p>Date: {{ date('d-m-Y', strtotime($appointment->booked_at)) }}</p>
                    <p>Time: {{ date('h:i A', strtotime($appointment->time)) }}</p>
                    <p>Status: <span class="@php
                        if ($appointment->status == "Booked") {
                            echo "status-booked";
                        }else if($appointment->status == "Pending"){
                            echo "status-booked";
                        }else{
                            echo "status-paid";
                        }
                    @endphp">{{$appointment->status}}</span></p>
                    @if ($appointment->status == "Pending" || $appointment->status == "Paid")
                    <p>Remark :<span>{{$appointment->remark}}</span></p>                   
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                          <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                              Medicines
                            </button>
                          </h2>
                          {{-- {{$appointment->medicines}} --}}
                          @php
                          $total_med_fee = 0;
                        @endphp
                          @foreach ($appointment->medicines as $medicine)
                
                          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="d-flex justify-content-between">
                                   <b>{{$medicine->name}}</b>
                                   <i> {{$medicine->pivot->amount}} &nbsp;{{"(RM".$medicine->price*$medicine->pivot->amount.")"}}</i>
                                </div>
                          </div>
                        </div>
                        @php
                        $total_med_fee += $medicine->price*$medicine->pivot->amount;
                        @endphp
                        @endforeach
                      </div>
                    @endif
                    @if ($appointment->status == "Pending")
                    <h3 class="mb-4 mt-4">Total Fee (RM): <input class="border-0" id="@role('doctor'){{"total_fee"}}@else{{"payment_fee"}}@endrole" name="fee" type="number"
                            readonly value="{{$appointment->fee}}"></h3>
                    @endif
                    @if ($appointment->status == "Paid")
                    <p class="mt-4">Consultation Fee (RM): {{$appointment->fee-$total_med_fee}}</p>
                    <h3 class="mb-4 mt-2">Total Paid (RM): <input class="border-0" id="@role('doctor'){{"total_fee"}}@else{{"payment_fee"}}@endrole" name="fee" type="number"
                            readonly value="{{$appointment->fee}}"></h3>
                    @endif
                </div>

                

                <div class="d-grid gap-2 mt-4">
                    @if ($appointment->status == "Booked")
                    <a href="/appointments/{{$appointment->id}}/edit" class="btn btn-dark"><img src="/logo/edit.svg" style="width: 23px" alt=""></a>
                    @elseif($appointment->status == "Pending" && auth()->user()->getRoleNames()->implode("") == "patient")
                    <a href="{{route('appointments.paymentdetails',$appointment->id)}}" class="btn btn-dark">Complete Your Payment <img src="/logo/pay.svg" style="width: 23px" alt=""></a>
                    @else
                    @endif
                    <a href="/dashboard" class="btn btn-outline-dark">Back To Dashboard</a>
                </div>
            </div>
        </div>


    </main>

</body>

</html>
