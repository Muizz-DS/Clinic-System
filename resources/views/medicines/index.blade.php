<!DOCTYPE html>
<html lang="en">
@include('templates.head')

<body>
    @include('templates.navbar')

    <main class="container">
        <h1 class="mt-5">Medicines List</h1>
        <table class="table mt-4">
            <thead>
              <tr>
                <th scope="col">No.</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Amount</th>
                <th scope="col">Price</th>
              </tr>
            </thead>
            <tbody>              
              @foreach ($medicines as $key => $medicine)
              <tr>
              <td>{{$key + 1}}</td>
              <td>{{$medicine->name}}</td>
              <td>{{$medicine->description}}</td>
              <td>{{$medicine->amount}}</td>
              <td>RM {{number_format($medicine->price, 2, '.', '')}}</td>
              </tr>                  
              @endforeach
            
            </tbody>
          </table>
    </main>

</body>

</html>
