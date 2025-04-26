<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('client.common.head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .header .branding {
        background-color: #3674B5 !important;
    }

    /* Add some simple styles */
    .container {
        text-align: center;
        margin-top: 50px;
    }

    .btn {
        padding: 10px 20px;
        background-color: #3674B5;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        font-size: 18px;
    }

    .btn:hover {
        background-color: #1f4e78;
    }
</style>

<body class="index-page">

    <main class="main">

        <!-- Hero Section -->
        <section id="apply" class="apply section accent-background">

            @include('client.common.navbar')


        </section><!-- /Hero Section -->
        <div class="container-fluid px-7"><br>

        </div><br>
        <!-- Application Form Section -->
        <div class="container">
            <p class="mt-1" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
                <button data-text="Awesome" class="buttonpma">
                    <span class="actual-text">&nbsp;Final&nbsp;Step&nbsp;</span>
                    <span class="hover-text" aria-hidden="true">&nbsp;Final&nbsp;Step&nbsp;</span>
                </button>
            </p>
            <h2>Almost There!</h2>
            <p>There is only one more step left to complete your application. Please confirm your application by sending the message "join direction-thrown" to us on WhatsApp.</p>
            <p>Click the link below to send the message:</p>
            <a href="https://wa.me/+14155238886?text=join%20direction-thrown" class="btn" target="_blank">Send "Join Direction-Thrown" on WhatsApp</a>
            <p>By clicking the link, you will be able to track your response and complete the process.</p>
        </div>



    </main><br>
    @include('client.common.footer')
    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>
    @include('client.common.scripts')

</body>

</html>