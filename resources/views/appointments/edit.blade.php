<!DOCTYPE html>
<html lang="en">
@include('templates.head')

<body>
    @include('templates.navbar')

    <main class="container">

        @if ($appointment->status == 'Pending')
            <h1 class="mt-5">Please Complete Your Payment</h1>
        @else
            <h1 class="mt-5">Edit an appointment</h1>
        @endif

        <div class="card mt-4">
            <div class="card-body">
                <form id="@if ($appointment->status == "Pending"){{"payment_form"}}@else{{"update_form"}}@endif" method="POST" action="@if($appointment->status == "Pending"){{route('appointments.payment', $appointment->id)}}@else{{ route('appointments.update', $appointment->id)}}@endif">
                @if ($appointment->status == "Pending")
                    @method('POST')
                @else
                    @method('PUT')
                @endif
                    @csrf
                    <div class="mb-3">
                        <label for="booking_date" class="form-label">Appointment with Dr ?</label>
                        <select @if ($appointment->status == 'Pending' || $appointment->status == 'Paid') {{ 'disabled' }} @endif
                            @role('doctor') {{ 'disabled' }} @endrole class="form-select" name="title"
                            id="title">
                            <option {{ $appointment->title == 'Raju' ? 'selected' : '' }} class="form-control"
                                value="Raju">Dr Raju</option>
                            <option {{ $appointment->title == 'Kamala' ? 'selected' : '' }} class="form-control"
                                value="Kamala">Dr Kamala</option>
                        </select>
                        <label for="booking_date" class="form-label">On what date you would like to book ?</label>
                        {{-- <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"> --}}
                        <input @if ($appointment->status == 'Pending' || $appointment->status == 'Paid') {{ 'disabled' }} @endif
                            @role('doctor') {{ 'disabled' }} @endrole type="text" class="form-control"
                            id="datepicker" name="booking"
                            value="{{ date('Y-m-d', strtotime($appointment->booked_at)) }}">
                        {{-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> --}}
                        <label for="booking_date" class="form-label mt-2">On what time slot would you prefer ?</label>
                        {{-- <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"> --}}
                        <input @if ($appointment->status == 'Pending' || $appointment->status == 'Paid') {{ 'disabled' }} @endif
                            @role('doctor') {{ 'disabled' }} @endrole type="time" class="form-control"
                            id="timepicker" min="08:00" max="20:00" name="time"
                            value="{{ $appointment->time }}">
                        @role('doctor')
                            <label for="remark" class="form-label mt-2">Remark</label>
                            {{-- <textarea class="form-control" name="remark" id="remark" cols="30" rows="10">Notes: &#13;&#10;&#13;&#10;&#13;&#10;&#13;&#10;Medication:</textarea> --}}
                            <textarea required class="form-control" name="remark" id="remark" cols="30" rows="10">{{$appointment->remark}}</textarea>
                            {{-- <label for="medication" class="form-label mt-2">Medication:</label> --}}
                            {{-- medication list --}}
                            <div class="accordion mt-2" id="accordionExample">
                                <div id="accordion" class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button label-font" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Medication
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="list-group">
                                                @foreach ($medicines as $key => $medicine)
                                                    <li class="list-group-item list-group-item-action">
                                                        <div id="medicine-list" class="d-flex">
                                                            <span
                                                                class="flex-grow-1 label-font">{{ $medicine->name }}</span>
                                                            <div id="{{ $medicine->id }}">
                                                                <span class="label-font" style="font-size: 14px"><i>(Amount:
                                                                        {{ $medicine->amount }})</i></span> &nbsp;
                                                                <button id="minus-btn-{{ $key + 1 }}" type="button"
                                                                    class="btn btn-dark"><img class="btn-logo"
                                                                        src="/logo/minus.svg" alt=""></button>
                                                                <span id="medicine-amount-{{ $medicine->id }}"
                                                                    aria-valuenow="{{ $medicine->amount }}"
                                                                    class="label-font mx-3">0</span>
                                                                <button id="add-btn-{{ $key + 1 }}" type="button"
                                                                    class="btn btn-dark"><img class="btn-logo"
                                                                        src="/logo/add.svg" alt=""></button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <input id="medicine-{{ $medicine->id }}" name="medicine[{{ $medicine->id }}]" type="text" hidden value="">
                                                    <input hidden id="cost_{{ $medicine->id }}" name="cost[{{ $medicine->id }}]" value="{{ $medicine->price }}" type="text">                                                    
                                                @endforeach
                                                <p class="text-decoration-none">{{ $medicines->links() }} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label for="medication" class="form-label mt-4">Consultation Fee (RM):</label>
                                <input id="consultation_fee" type="number" step=".01"
                                    placeholder="Please Enter Consultation Fee" value="10.00" pattern="^\d*\.?\d{1,2}$">
                            </div>
                        @endrole

                        @if (auth()->user()->getRoleNames()->implode('') == 'patient' && $appointment->status == 'Pending')
                            <label for="remark" class="form-label mt-2">Remark</label>
                            <textarea disabled class="form-control" name="remark" id="remark" cols="30" rows="10">{{ $appointment->remark }}</textarea>
                            <div class="accordion mt-4" id="accordionExample">
                                <div class="accordion-item">
                                  <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                      Medicines
                                    </button>
                                  </h2>
                                  
                                  @foreach ($appointment->medicines as $medicine)
                                  <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="d-flex justify-content-between">
                                           <b>{{$medicine->name}}</b>
                                            {{$medicine->pivot->amount}}
                                        </div>
                                  </div>
                                </div>
                                @endforeach
                              </div>
                            </div>                     
                        @endif
                        @hasanyrole('doctor|admin')
                            <label for="booking_date" class="form-label mt-2">Status</label>
                            <select class="form-select" name="status" id="status">
                                @if ($appointment->status == 'Booked')
                                    <option selected class="form-control" value="Booked">Booked</option>
                                    <option class="form-control" value="Pending">Pending Payment</option>                                             
                                @else
                                    <option class="form-control" value="Booked">Booked</option>
                                    <option selected class="form-control" value="Pending">Pending Payment</option>                                   
                                @endif
                            </select>
                        @endrole
                    </div>
                    @if ($appointment->status == "Pending"|| auth()->user()->getRoleNames()->implode("") == "doctor")
                    <h3 class="mb-4">Total Fee (RM): <input class="border-0" id="@role('doctor'){{"total_fee"}}@else{{"payment_fee"}}@endrole" name="fee" type="number"
                            readonly value="{{$appointment->fee}}"></h3>
                    @endif
                    @if ($appointment->status != 'Pending')
                    <button id="edit-appointment-btn" type="submit" class="btn btn-dark btn-font">Edit
                        appointment</button>
                    </form>
                    @else
                    <button id="pay-appointment-btn" type="submit" class="btn btn-dark btn-font">Proceed
                        Payment</button>
                    @endif

            </div>
        </div>
    </main>
</body>

</html>

<script>
    let medicines_count = $("li").length;
    const ori_status = $("#status").val();
    let consultation_fee = 10.00;
    let cost_accumulator = 0;
    let total_fee = 0;
    parseFloat($("#consultation_fee").val(consultation_fee));
    total_fee = $("#total_fee").val(consultation_fee);

    $(document).ready(function() {

        $("input#consultation_fee").change(function() {
            consultation_fee = parseFloat($("#consultation_fee").val());
            total_fee = cost_accumulator + consultation_fee;
            $("#total_fee").val(total_fee);
        });

        for (let index = 1; index <= medicines_count; index++) {

            $(`#minus-btn-${index}`).click(function() {
                let medicine_id = $(this).parent().attr('id');
                let medicine_amount = $("#medicine-amount-" + medicine_id).html();
                if (medicine_amount > 0.00) {

                    $("#medicine-amount-" + medicine_id).html(parseInt(medicine_amount) - 1);
                    $(`#medicine-${index}`).val(parseInt(medicine_amount) - 1);
                    cost_accumulator -= (parseFloat($("#cost_" + medicine_id).val())).toFixed(2);
                    cost_accumulator = toFixedNumber(cost_accumulator, 2, 10);
                    if (cost_accumulator > consultation_fee) {
                        $("#total_fee").val(cost_accumulator - consultation_fee);
                    } else {
                        $("#total_fee").val(consultation_fee - cost_accumulator);
                    }

                }
                console.log(cost_accumulator);

            });

            $(`#add-btn-${index}`).click(function() {
                let medicine_id = $(this).parent().attr('id');
                let medicine_amount = $("#medicine-amount-" + medicine_id).html();
                const ori_amount = $(this).prev().attr('aria-valuenow');

                if (medicine_amount < ori_amount) {
                    $("#medicine-amount-" + medicine_id).html(parseInt(medicine_amount) + 1);
                    $(`#medicine-${index}`).val(parseInt(medicine_amount) + 1);
                    cost_accumulator += parseFloat($("#cost_" + medicine_id).val());
                    cost_accumulator = toFixedNumber(cost_accumulator, 2, 10);
                    $("#total_fee").val(consultation_fee + cost_accumulator)
                }
                // console.log(typeof test);

            });
            // implement save to localstorage to support pagination

        }
        @role('doctor')  
        $("#update_form").on("submit", function(event) {
            if ($("#status").val() == ori_status) {
                alert("Please update status");
                event.preventDefault();
            }
        });
        @endrole
    });

    function toFixedNumber(num, digits, base) {
        const pow = Math.pow(base ?? 10, digits);
        return Math.round(num * pow) / pow;
    }
</script>
