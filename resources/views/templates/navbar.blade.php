<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="/dashboard"><img src="{{asset('logo/logo.svg')}}" alt="" class="logo" style="max-width: 30px;">&nbsp;<span class="logo-font">Clinic System</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav navbar-font">
          @role('patient')
          <a class="nav-link active" aria-current="page" href="/appointments/create">Book Appointment</a>
          @endrole
          @hasrole('doctor|admin')
          <a class="nav-link" href="/medicines">Medications</a>
          @endhasrole
          @role('admin')
          <a class="nav-link" href="admin">Admin</a>
          @endrole
          <a class="nav-link" aria-current="page" href="/history">History</a>
          {{-- <a class="nav-link disabled" aria-disabled="true">Disabled</a> --}}
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link class="nav-link" :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-dropdown-link>
        </form>
        </div>
      </div>
    </div>
  </nav>