@if (count($user_appointments) > 0)
    @role('patient')
        <h1>
            Don't Forget Your Appointment :)</h1>
    @endrole
    <table class="table mt-4">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Appointment</th>
                <th scope="col">Time Slot</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user_appointments as $key => $appointment)
                <tr>
                    <th scope="row">{{ $key + 1 }}</th>
                    <td>{{ $appointment->title }}</td>
                    <td>{{ date('h:i A', strtotime($appointment->time)) }}</td>
                    <td>{{ date('d-m-Y', strtotime($appointment->booked_at)) }}</td>
                    {{-- <td><span class="{{($appointment->status == 'Booked') ? "status-booked" :"" }}">{{ $appointment->status }}</span></td> --}}
                    <td><span
                            class="@php
                                if ($appointment->status == "Booked") {
                                  echo "status-booked";
                                }else if ($appointment->status == "Prescription"){
                                  echo "status-booked";
                                }else if ($appointment->status == "Pending"){
                                  echo "status-booked";
                                }else if ($appointment->status == "Paid"){
                                  echo "status-paid";
                                } @endphp">
                            @if ($appointment->status == 'Booked')
                                {{ 'Appointment Booked' }}
                            @elseif($appointment->status == 'Prescription')
                                {{ 'Pending Prescription' }}
                            @elseif($appointment->status == 'Pending')
                                {{ 'Pending Payment' }}
                            @elseif($appointment->status == 'Paid')
                                {{ 'Paid' }}
                            @endif
                        </span>
                    </td>
                    <td>
                        <div class="d-flex">
                            @hasanyrole('doctor|admin')
                                @if ($appointment->status == 'Pending' && auth()->user()->getRoleNames()->implode("") == "doctor")
                                <a href="appointments/{{ $appointment->id }}/edit" class="btn btn-dark"
                                    data-bs-toggle="tooltip" data-bs-title="Default tooltip"><img src="/logo/edit.svg"
                                        style="width: 23px" alt=""></a>&nbsp;
                                @else
                                        <a href="appointments/{{ $appointment->id }}" class="btn btn-dark"
                                            data-bs-toggle="tooltip" data-bs-title="Default tooltip"><img src="/logo/eye.svg"
                                              style="width: 23px" alt=""></a>&nbsp;
                                @endif
                            @else                            
                                @if ($appointment->status == 'Pending')
                                <a href="appointments/{{ $appointment->id }}" class="btn btn-dark"
                                    data-bs-toggle="tooltip" data-bs-title="Default tooltip"><img src="/logo/pay.svg"
                                      style="width: 23px" alt=""></a>&nbsp;
                                {{-- <form action="{{route('appointments.payment',$appointment->id)}}" method="post">
                                    @csrf
                                    <button class="btn btn-dark"
                                        data-bs-toggle="tooltip" data-bs-title="Default tooltip"><img src="/logo/pay.svg"
                                            style="width: 23px" alt=""></button>
                                </form>&nbsp; --}}
                                @else
                                    <a href="appointments/{{ $appointment->id }}" class="btn btn-dark"
                                        data-bs-toggle="tooltip" data-bs-title="Default tooltip"><img src="/logo/info.svg"
                                          style="width: 23px" alt=""></a>&nbsp;
                                @endif
                            @endhasanyrole
                            <form id="delete-appointment" action="appointments/{{ $appointment->id }}" method="POST">
                                @csrf
                                @method('DELETE')<button type="submit" class="btn btn-danger"><img
                                        src="/logo/trash6.svg" style="width: 23px" alt=""></button></form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <h1>It seems empty over here</h1>
    @role('patient')
    <a href="/appointments/create" class="btn btn-dark mt-2 btn-font" type="button">Book an appointment now !</a>
    @endrole
    <div style="display: flex; justify-content: flex-end;">
        <img style="max-width: 33%; margin-right: 5%;" src="/logo/doctor-dashboard.svg" alt="">
    </div>
@endif
