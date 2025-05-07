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
        if (!('webkitSpeechRecognition' in window) || !window.speechSynthesis) {
            alert("Your browser doesn't support voice features. Please use Chrome.");
            return;
        }

        const synth = window.speechSynthesis;
        const recognition = new webkitSpeechRecognition();
        recognition.lang = 'en-US';
        recognition.interimResults = false;
        recognition.continuous = false;

        let currentStep = 'ask-assist';

        const speak = (text, callback) => {
            const utter = new SpeechSynthesisUtterance(text);
            utter.onend = callback;
            synth.cancel();
            synth.speak(utter);
        };

        const isValidEmail = (email) => {
            const pattern = /^[a-z]+\.[a-z]+@school\.com$/;
            return pattern.test(email);
        };

        const isValidPassword = (password) => {
            return /^[a-z0-9]+$/.test(password);
        };

        const askNext = () => {
            if (currentStep === 'email') {
                speak("Please say your email.", () => recognition.start());
            } else if (currentStep === 'password') {
                speak("Now, say your password.", () => recognition.start());
            } else if (currentStep === 'confirm') {
                speak("Do you want to sign in now? Say yes to continue or no to restart.", () => recognition.start());
            }
        };

        recognition.onresult = (event) => {
            let spokenText = event.results[0][0].transcript.toLowerCase().trim().replace(/\.$/, '');
            console.log("Recognized:", spokenText);

            if (currentStep === 'ask-assist') {
                if (spokenText.includes("yes")) {
                    speak("Great, let's begin.", () => {
                        currentStep = 'email';
                        askNext();
                    });
                } else {
                    speak("Voice assistance is turned off.");
                }
                return;
            }

            if (currentStep === 'email') {
                spokenText = spokenText
                    .replace(" at ", "@")
                    .replace(" dot ", ".")
                    .replace(/\s/g, '')
                    .replace(/\.$/, '');

                if (!isValidEmail(spokenText)) {
                    speak("Invalid email format. Please try again.", () => askNext());
                    return;
                }

                document.getElementById("email").value = spokenText;
                document.getElementById("email").dispatchEvent(new Event('input'));
                @this.set('email', spokenText);
                speak("Email received.", () => {
                    currentStep = 'password';
                    askNext();
                });
            } else if (currentStep === 'password') {
                // Break down spoken characters and filter only valid ones (letters/numbers)
                const chars = spokenText
                    .toLowerCase()
                    .replace(/\s/g, '') // remove all spaces
                    .split('') // split into characters
                    .filter(c => /[a-z0-9]/.test(c)); // keep only valid characters

                const password = chars.join('');

                if (!isValidPassword(password)) {
                    speak("Invalid password format. Please try again.", () => askNext());
                    return;
                }

                document.getElementById("password").value = password;
                document.getElementById("password").dispatchEvent(new Event('input'));
                @this.set('password', password);

                speak("Password saved.", () => {
                    currentStep = 'confirm';
                    askNext();
                });
            } else if (currentStep === 'confirm') {
                if (spokenText.includes("yes")) {
                    speak("Signing you in now.", () => {
                        document.querySelector('button[type=\"submit\"]').click();
                    });
                } else {
                    speak("Restarting login process.", () => {
                        currentStep = 'email';
                        askNext();
                    });
                }
            }
        };

        recognition.onerror = (event) => {
            console.error('Recognition error:', event.error);
            speak("Sorry, I didn't catch that. Let's try again.", () => askNext());
        };

        setTimeout(() => {
            speak("Welcome. Would you like voice assistance to log in? Say yes or no.", () => recognition.start());
        }, 1000);
    });

    // Login failed handler with retry
    window.addEventListener('login-failed', () => {
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: 'Invalid email or password. Would you like to try again?',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Try Again'
        }).then(() => {
            const retryUtter = new SpeechSynthesisUtterance("Let's try again from the beginning.");
            retryUtter.onend = () => location.reload();
            window.speechSynthesis.speak(retryUtter);
        });
    });
</script>