<x-layouts.app>
    <h2>👀 Parent Camera Viewer</h2>

    <label for="studentSelect">Select Your Child:</label>
    <select id="studentSelect">
        @foreach ($students as $student)
        <option value="{{ $student->id }}">{{ $student->application->first_name }}</option>
        @endforeach
    </select>

    <button id="requestCameraBtn">Request Camera</button>
    <video id="parentVideo" autoplay playsinline style="width: 100%; max-width: 600px;"></video>

    <script src="https://cdn.jsdelivr.net/npm/simple-peer@9.11.1/simplepeer.min.js"></script>
    <script>
        let peer;

        document.getElementById('requestCameraBtn').addEventListener('click', () => {
            const studentId = document.getElementById('studentSelect').value;

            fetch(`/camera/request/${studentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            setTimeout(() => {
                fetch(`/camera/get-signal/${studentId}`)
                    .then(res => res.json())
                    .then(data => {
                        const parsedSignal = typeof data.signal === 'string' ? JSON.parse(data.signal) : data.signal;
                        if (parsedSignal && parsedSignal.sdp) {
                            parsedSignal.sdp = parsedSignal.sdp
                                .split('\r\n')
                                .filter(line => !line.includes('a=max-message-size'))
                                .join('\r\n');
                            startWebRTC(false, parsedSignal);
                        } else {
                            alert("No signal received from student yet.");
                        }
                    });
            }, 5000);
        });

        function startWebRTC(initiator, studentSignal) {
            peer = new SimplePeer({
                initiator,
                trickle: false,
                config: {
                    iceServers: [{
                        urls: 'stun:stun.l.google.com:19302'
                    }]
                }
            });

            peer.on('signal', data => {
                console.log('Parent signal (not needed if one-way):', data);
            });

            peer.on('stream', stream => {
                document.getElementById('parentVideo').srcObject = stream;
            });

            peer.signal(studentSignal);
        }
    </script>
</x-layouts.app>