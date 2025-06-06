<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url("/home") }}" class="brand-link">
        <img src="{{ Storage::url("tea-logo.svg") }}" alt="Logo" class="brand-image bg-white img-circle elevation-3" style="padding:2px;opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
     <!-- Sidebar -->
     <div class="sidebar">
        <nav class="mt-2">
                <x-menu />
        </nav>
     </div>
    <!-- /.sidebar -->
  </aside>