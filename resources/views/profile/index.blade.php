<x-layouts.app>
    <div class="container py-5">
        <h3 class="text-center fw-bold mb-4" style="color: #3674B5;">

            Manage Your Profile
        </h3>

        {{-- SweetAlert --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if(session('success'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3674B5'
                });
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#3674B5'
                });
            });
        </script>
        @endif

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold" id="update-tab" data-bs-toggle="tab" data-bs-target="#update" type="button" role="tab">
                    👤Update Profile
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                    <img src="https://img.icons8.com/color/48/lock.png" width="20" class="me-1" />
                    Change Password
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabContent">
            {{-- Update Profile --}}
            <div class="tab-pane fade show active" id="update" role="tabpanel">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header text-white rounded-top-4" style="background: linear-gradient(to right, #3674B5, #2e5b8c);">
                        <h5 class="mb-0 py-2 text-center">
                            Update Your Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" class="form-control rounded-3" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <button type="submit" class="btn w-100 text-white fw-semibold" style="background-color: #3674B5;">
                                <img src="https://img.icons8.com/fluency/24/save-close.png" width="20" class="me-2" />
                                Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="tab-pane fade" id="password" role="tabpanel">
                <div class="card shadow-lg border-0 rounded-4 mt-4 mt-md-0">
                    <div class="card-header text-white rounded-top-4" style="background: linear-gradient(to right, #3674B5, #2e5b8c);">
                        <h5 class="mb-0 py-2 text-center">
                            <img src="https://img.icons8.com/color/48/lock.png" width="24" class="me-2" />
                            Change Your Password
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Current Password</label>
                                <input type="password" name="current_password" class="form-control rounded-3" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">New Password</label>
                                <input type="password" name="new_password" class="form-control rounded-3" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control rounded-3" required>
                            </div>
                            <button type="submit" class="btn w-100 text-white fw-semibold" style="background-color: #3674B5;">
                                <img src="https://img.icons8.com/color/48/lock.png" width="20" class="me-1" />
                                Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>