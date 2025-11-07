<!-- Bottom Navbar (Material Design Style - only mobile) -->
<nav class="d-md-none fixed-bottom shadow-lg border-0" id="mobile-navigation" style="background: #fff; border-radius: 16px 16px 0 0;">
  <div class="container-fluid d-flex justify-content-around py-2">

    <!-- Profile -->
    <a href="{{ route('user-profile.profile') }}" class="nav-item text-decoration-none text-dark d-flex flex-column align-items-center">
      <i class="bi bi-person-fill fs-3 mb-1"></i>
      <span class="fw-bold small">پروفایل</span>
    </a>

    <!-- Home -->
    <a href="{{ route('admin.dashboard') }}" class="nav-item text-decoration-none text-dark d-flex flex-column align-items-center">
      <i class="bi bi-house-door-fill fs-3 mb-1"></i>
      <span class="fw-bold small">خانه</span>
    </a>

    <!-- Logout -->
    <a href="{{ route('logout') }}" class="nav-item text-decoration-none text-dark d-flex flex-column align-items-center">
      <i class="bi bi-box-arrow-right fs-3 mb-1"></i>
      <span class="fw-bold small">خروج</span>
    </a>

  </div>
</nav>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
  nav a {
    transition: all 0.3s ease;
    padding: 6px;
    border-radius: 12px;
  }
  nav a:hover {
    background: rgba(37, 117, 252, 0.08);
    color: #2575fc !important;
  }
  nav a.active {
    color: #2575fc !important;
  }
</style>
