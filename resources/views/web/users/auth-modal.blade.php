<!-- User Login / Register Modal -->
<div class="modal fade" id="userAuthModal" tabindex="-1" aria-labelledby="userAuthModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header">
        <h5 class="modal-title" id="userAuthModalLabel">Login or Register</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- Tabs -->
        <ul class="nav nav-tabs" id="authTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginTab" type="button"
              role="tab">Login</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerTab" type="button"
              role="tab">Register</button>
          </li>
        </ul>

        <div class="tab-content mt-3" id="authTabsContent">
          <!-- Login Form -->
          <div class="tab-pane fade show active" id="loginTab" role="tabpanel">
            <form method="POST" action="{{ route('user.login') }}">
              @csrf
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
          </div>

          <!-- Register Form -->
          <div class="tab-pane fade" id="registerTab" role="tabpanel">
            <form method="POST" action="{{ route('user.register') }}">
              @csrf
              <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>