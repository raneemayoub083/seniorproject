<section class="">
    <!-- Background Video -->
    <video autoplay muted loop playsinline class="position-absolute ">
        <source src="{{ asset('assets/videos/bg.mp4') }}" type="video/mp4">
    </video>
    <div class="dark-overlay position-absolute w-100 h-100 top-0 start-0"></div>
    <!-- Content -->
    <div class="page-header section-height-75 position-relative z-index-1">
        <div class="container">
            <div class="row">
                <div class="col-6 mt-8" style="margin-right: 80px;">
                    <h3 class="font-weight-bolder text-info text-gradient">{{ __('Welcome ') }}</h3>
                    <form wire:submit.prevent="login" action="{{ route('login') }}" method="POST">

                        <div class="mb-3">
                            <label for="email" class="l">{{ __('Email: ') }}</label>
                            <input wire:model.live="email" id="email" type="email" class="form-control"
                                placeholder="Email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="l">{{ __('Password:') }}</label>
                            <input wire:model.live="password" id="password" type="password" class="form-control"
                                placeholder="Password">
                        </div>
                        <div class="text-center">
                            <button type="submit"
                                class="btn bg-gradient-info w-50 mt-4 mb-0">{{ __('Sign in') }}</button>
                        </div>
                    </form>
                </div>

                <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mt-8">
                    <img src="../assets/img/visionvoicelogo.png" alt="Logo" class="rotating">
                </div>

            </div>
        </div>
    </div>
    <style>
        video {
            object-fit: cover;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* Define the keyframes for the rotation animation */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Apply the animation to elements with the 'rotating' class */
        .rotating {
            animation: spin 150s linear infinite;
        }
    </style>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('login-success', event => {
        Swal.fire({
            icon: 'success',
            title: 'Login Approved',
            text: 'Welcome back!',
            confirmButtonColor: '#3085d6',
            showConfirmButton: false, // Remove the OK button
            timer: 1500 // Automatically close the SweetAlert after 1.5 seconds
        });

        // Redirect to the dashboard after SweetAlert closes
        setTimeout(() => {
            window.location.href = "{{ url('/dashboard') }}"; // Redirect after login
        }, 1500); // Match the timer duration to allow for redirection after closing the alert
    });


    window.addEventListener('login-failed', event => {
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: 'Invalid email or password. Please try again!',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (!('webkitSpeechRecognition' in window)) {
            alert("Your browser does not support speech recognition. Please use Google Chrome.");
            return;
        }

        let recognition = new webkitSpeechRecognition();
        recognition.lang = 'en-US';
        recognition.continuous = true; // Keep listening forever
        recognition.interimResults = false;

        recognition.onstart = function() {
            Swal.fire({
                icon: 'info',
                title: 'Listening...',
                text: 'Say "Email", "Password", "Clear", "Delete", or "Sign in"',
                timer: 4000,
                showConfirmButton: false
            });
        };

        recognition.onresult = function(event) {
            let result = event.results[event.results.length - 1][0].transcript.toLowerCase().trim();
            console.log("Recognized: ", result);

            // Fix special characters and cleanup the text
            result = result.replace(" at ", "@");
            result = result.replace(" dot ", ".");
            result = result.replace(/\s+/g, ''); // Remove all spaces

            // Handle "email" command
            if (result.includes("email")) {
                let email = result.replace("email", "").trim();
                document.getElementById("email").value = email;
                @this.set('email', email); // Update Livewire email field
                Swal.fire({
                    icon: 'success',
                    title: 'Email Added ✅',
                    text: email,
                    timer: 3000,
                    showConfirmButton: false
                });
            }

            // Handle "password" command
            if (result.includes("password")) {
                let password = result.replace("password", "").trim();
                document.getElementById("password").value = password;
                @this.set('password', password); // Update Livewire password field
                Swal.fire({
                    icon: 'success',
                    title: 'Password Added 🔑',
                    text: "Secured 😎",
                    timer: 3000,
                    showConfirmButton: false
                });
            }

            // Handle "clear" or "delete" command
            if (result.includes("clear") || result.includes("delete")) {
                document.getElementById("email").value = '';
                document.getElementById("password").value = '';
                @this.set('email', ''); // Clear Livewire email field
                @this.set('password', ''); // Clear Livewire password field
                Swal.fire({
                    icon: 'info',
                    title: 'Fields Cleared',
                    text: 'Email and Password have been cleared.',
                    timer: 3000,
                    showConfirmButton: false
                });
            }

            // Handle "login" or "sign in" command
            if (result.includes("login") || result.includes("signin")) {
                Swal.fire({
                    icon: 'success',
                    title: 'Signing in...',
                    text: 'Please wait',
                    timer: 3000,
                    showConfirmButton: false
                });
                @this.call('login'); // Call the Livewire login method
            }
        };

        recognition.onerror = function(event) {
            console.error('Speech recognition error', event.error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Speech recognition error, please try again!',
            });
        };

        recognition.onend = function() {
            // Restart the recognition when it ends
            recognition.start();
        };

        recognition.start();
    });
</script>