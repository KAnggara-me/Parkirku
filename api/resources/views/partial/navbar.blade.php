<nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
  <div class="container">
    <a class="navbar-brand" href="/">ParkirKu</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-lg-0 mb-2">
        <li class="nav-item">
          <a class="nav-link {{ $title === 'Home' ? 'active' : '' }}" aria-current="page" href="/">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title === 'Review' ? 'active' : '' }}" href="/review">Review</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ $title === 'Report' ? 'active' : '' }}" href="/report">Report</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
