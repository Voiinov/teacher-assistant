<?php $username = Auth::user()->last_name  . " " . Auth::user()->first_name ?>
<?php $avatar = !is_null(Auth::user()->avatar) ?  Storage::url("img/avatars/" . Auth::user()->avatar) : Storage::url("img/avatars/default.jpg") ?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('home') }}" class="nav-link">{{ __("Dashboard") }}</a>
      </li>
    </ul>
    @php Storage::exists("img/avatars/" . Auth::user()->avatar) @endphp
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
      </li>
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="{{ $avatar }}" class="user-image img-circle elevation-2" alt="{{ Auth::user()->first_name }}">
          <span class="d-none d-md-inline">{{ Auth::user()->first_name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-primary">
            <img src="{{ $avatar }}" class="img-circle elevation-2" alt="{{ $username }}">
            <p>
              {{ $username }} - {{ Str::words(Auth::user()->role->pluck("name")->implode(", "),3) }}
                
              <small>{{ __('Registered') }}: {{ __(date("d.m.Y", strtotime(Auth::user()->created_at))) }} </small>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="{{route("users.profile")}}" class="btn btn-default btn-flat">{{ __('Profile') }}</a>
            <a href="{{ route('logout') }}" class="btn btn-default btn-flat float-right" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><?= __('Logout') ?></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>          
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
