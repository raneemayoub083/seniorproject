<x-layouts.app>
    <h2>🎥 Student Camera Page</h2>
    <video id="studentVideo" autoplay muted playsinline style="width: 100%; max-width: 600px;"></video>

    <script src="https://cdn.jsdelivr.net/npm/simple-peer@9.11.1/simplepeer.min.js"></script>
    @php
    $student = \App\Models\Student::where('user_id', auth()->id())->first();
    @endphp
    <script>
        let studentStream;
        let peer;
        const studentId = @json($student -> id);

        navigator.mediaDevices.getUserMedia({
            video: true,
            audio: false
        }).then(stream => {
            studentStream = stream;
            document.getElementById('studentVideo').srcObject = stream;
            checkForParentRequest();
        });

        function checkForParentRequest() {
            setInterval(() => {
                fetch(`/camera/check-request/${studentId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.stream && !peer) {
                            startWebRTC(true);
                        }
                    });
            }, 3000);
        }

        function startWebRTC(initiator) {
            peer = new SimplePeer({
                initiator,
                trickle: false,
                stream: studentStream,
                config: {
                    iceServers: [{
                        urls: 'stun:stun.l.google.com:19302'
                    }]
                }
            });

            peer.on('signal', data => {
                fetch("/camera/send-signal", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        student_id: studentId,
                        signal: JSON.stringify(data)
                    })
                });
            });

            peer.on('connect', () => {
                console.log('📶 WebRTC connected to parent!');
            });
        }
    </script>
</x-layouts.app>