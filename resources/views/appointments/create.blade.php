<!DOCTYPE html>
<html lang="en">
@include('templates.head')
<body>
    @include('templates.navbar')

   <main class="container">
    
    <h1 class="mt-5">Book an appointment</h1>

    <div class="card mt-4">
        <div class="card-body">
            <form method="POST" action="{{route('appointments.store')}}">                
                @csrf
                <div class="mb-3">
                  <label for="booking_date" class="form-label">Appointment with Dr ?</label>
                  <select  class="form-control" name="title" id="title">
                    <option  class="form-control" value="Raju">Dr Raju</option>
                    <option  class="form-control" value="Kamala">Dr Kamala</option>
                  </select>
                  <label for="booking_date" class="form-label">On what date you would like to book ?</label>
                  {{-- <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"> --}}
                  <input type="text" class="form-control" id="datepicker" name="booking">
                  {{-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> --}}
                  <label for="booking_date" class="form-label mt-2">On what time slot would you prefer ?</label>
                  {{-- <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"> --}}
                  <input type="time" class="form-control" id="timepicker" min="08:00" max="20:00" name="time">
                </div>
                <button type="submit" class="btn btn-dark btn-font">Book appointment</button>
              </form>
        </div>
      </div>



   </main>
    
</body>
</html>