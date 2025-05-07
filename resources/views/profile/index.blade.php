<x-layouts.app>
    <div class="container mt-4">
        <h3 class="text-center mb-4" style="color: #3674B5">Update Profile</h3>

        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3674B5'
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3674B5'
            });
        </script>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="card p-4 shadow-sm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <button type="submit" class="btn text-white" style="background-color: #3674B5">Update Profile</button>
        </form>

        <hr class="my-4">

        <h4 class="text-center" style="color: #3674B5">Change Password</h4>
        <form method="POST" action="{{ route('profile.password') }}" class="card p-4 shadow-sm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn text-white" style="background-color: #3674B5">Change Password</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</x-layouts.app>