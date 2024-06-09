<!DOCTYPE html>
<html lang="en">
@include('templates.head')

<body>
    @include('templates.navbar')

    <main class="container">
        <h1 class="mt-5">Users List</h1>
        <table class="table mt-4">
            <thead>
              <tr>
                <th scope="col">No.</th>
                <th scope="col">Name</th>
                <th scope="col">Role</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>              
              @foreach ($users as $key => $user)
              <tr>
              <td>{{$key + 1}}</td>
              <td>{{$user->name}}</td>
              <td>{{$user->getRoleNames()->implode('')}}</td>
              <td><form action="users/{{ $user->id }}" method="POST">
                @csrf
                @method('DELETE')<button type="submit" class="btn btn-danger"><img
                        src="/logo/trash6.svg" style="width: 23px" alt=""></button></form></td>
              </tr>                  
              @endforeach
            
            </tbody>
          </table>
    </main>

</body>

</html>
