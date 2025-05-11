<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Face</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ✅ Face API -->
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <!-- ✅ Styling -->
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        video#background {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -2;
        }

        .dark-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
        }

        button {
            margin-top: 15px;
            padding: 10px 25px;
            background-color: #3674B5;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2a5d8f;
        }

        pre {
            margin-top: 15px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 10px;
            color: #ddd;
            max-height: 150px;
            overflow: auto;
        }

        video#video {
            border: 3px solid #fff;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <!-- Background video -->
    <video autoplay muted loop id="background">
        <source src="{{ asset('assets/videos/bg.mp4') }}" type="video/mp4">
    </video>
    <div class="dark-overlay"></div>

    <div class="content">
        <h2>Register Your Face ID</h2>
        <video id="video" width="320" height="240" autoplay muted></video><br>
        <button id="capture">Capture Face</button>
        <pre id="output"></pre>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script defer>
        document.addEventListener("DOMContentLoaded", function() {
            const video = document.getElementById('video');
            const output = document.getElementById('output');

            Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('/models')
            ]).then(startVideo);

            function startVideo() {
                navigator.mediaDevices.getUserMedia({
                        video: {}
                    })
                    .then(stream => video.srcObject = stream)
                    .catch(err => console.error("Camera error:", err));
            }

            document.getElementById('capture').addEventListener('click', async () => {
                const detection = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Face Not Detected',
                        text: 'Please ensure your face is visible and try again.',
                    });
                    return;
                }

                const descriptor = Array.from(detection.descriptor);
                output.textContent = JSON.stringify(descriptor, null, 2);

                fetch('/face/save-descriptor', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            descriptor
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Face Saved!',
                            text: 'Your Face ID has been registered successfully.',
                            confirmButtonColor: '#3674B5'
                        });
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: 'Something went wrong while saving your face.',
                            confirmButtonColor: '#d33'
                        });
                    });
            });

        });
    </script>

</body>

</html>