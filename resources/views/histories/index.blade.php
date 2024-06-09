<!DOCTYPE html>
<html lang="en">
@include('templates.head')

<body>
    @include('templates.navbar')

    <main class="container">
        <h1 class="mt-5">User History</h1>
        <table class="table mt-4">
            <thead>
              <tr>
                <th scope="col">No.</th>
                <th scope="col">Activity</th>
                <th scope="col">Timestamp</th>
              </tr>
            </thead>
            <tbody>              
              @foreach ($activities as $key => $activity)
              <tr>
              <td>{{$key + 1}}</td>
              <td>{{$activity->description}}</td>
              <td>{{$activity->created_at}}</td>
              </tr>                  
              @endforeach
            
            </tbody>
          </table>
    </main>

</body>

</html>
