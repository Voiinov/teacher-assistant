<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
   <li class="nav-item">
      <a href="<?= url('/home') ?>" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p><?= __("Dashboard")?></p>
      </a>
  </li>
  <li class="nav-item">
      <a href="<?= url('/users') ?>" class="nav-link">
          <i class="nav-icon fas fa-users"></i>
          <p><?= __("Users")?></p>
      </a>
  </li>
  <li class="nav-header"><?= __('Educational process') ?></li>
  <li class="nav-item">
      <a href="<?= url('/student') ?>" class="nav-link">
        <i class="nav-icon fas fa-graduation-cap"></i>
        <p><?= __("Learners")?></p>
    </a>
</li>
<li class="nav-item">
    <a href="<?= url('/groups') ?>" class="nav-link">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p><?= __("Groups")?></p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route("gradebook.index") }}" class="nav-link">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p><?= __("Journals")?></p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('subject.index') }}" class="nav-link">
        <i class="nav-icon fas fa-book"></i>
        <p><?= __("Subjects")?></p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('timetable.index') }}" class="nav-link">
        <i class="nav-icon fas fa-calendar"></i>
        <p><?= __("Timetable")?></p>
    </a>
</li>
<li class="nav-header"><?= __('Office work') ?></li>
<li class="nav-item">
    <a href="{{ route('minutebook.index') }}" class="nav-link">
        <i class="nav-icon fas fa-book"></i>
        <p><?= __("Minute book")?></p>
    </a>
</li>
</ul>