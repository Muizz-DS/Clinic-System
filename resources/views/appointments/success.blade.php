<!DOCTYPE html>
<html lang="en">
@include('templates.head')

<body>
    @include('templates.navbar')

    <main class="container">

        <h1 class="mt-5">Appointment booked successfully</h1>

        <div class="card mt-5 m-auto" style="max-width:500px; min-width: 400px;">
            <div class="card-body">
                <div class="text-center">
                    <p class="card-text">Dear <i>{{auth()->user()->implode('name')}}</i>,</p>
                    <p class="card-text">Your appointment has been booked at <b>{{date("h:i A",strtotime($booking->time))}}</b> on <b>{{date('d-m-Y', strtotime($booking->booked_at)) }}</b> with <b>{{ltrim($booking->title,"Appointment with") }}</b></p>
                    <p class="card-text">Thank for dealing with Klinik Kita</p>
                    <img src="/logo/tick2.svg" alt="" style="max-width: 100px">
                </div>
                <div class="d-grid gap-2 mt-4">
                <a href="/dashboard" class="btn btn-dark">Back To Dashboard</a>
                </div>
            </div>
          </div>
        

    </main>

</body>

</html>
