<x-layouts.app>
    <div class="container py-4">
        <h3 class="text-center mb-5 fw-bold" style="color: #3674B5;">📬 Parent-Teacher Chat</h3>

        <div class="row">
            <!-- Contacts List -->
            <div class="col-md-4">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header text-center fw-bold bg-white" style="color: #3674B5;">👥 Contacts</div>
                    <ul class="list-group list-group-flush">
                        @forelse ($contacts as $contact)
                        <li class="list-group-item border-0 {{ optional($receiver)->id === $contact['user']->id ? 'bg-primary text-white' : '' }} rounded-0">
                            <a href="{{ route('chat.index', $contact['user']->id) }}"
                                class="text-decoration-none d-flex justify-content-between align-items-center {{ optional($receiver)->id === $contact['user']->id ? 'text-white' : 'text-dark' }}">
                                <div>
                                    <strong>{{ $contact['user']->name }}</strong>
                                    <br>
                                    <small class="{{ optional($receiver)->id === $contact['user']->id ? 'text-white-50' : 'text-muted' }}">{{ $contact['subject'] }}</small>
                                </div>

                                @php
                                $unread = $unreadCounts[$contact['user']->id] ?? 0;
                                @endphp

                                @if ($unread > 0 && optional($receiver)->id !== $contact['user']->id)
                                <span class="badge rounded-pill bg-danger">{{ $unread }}</span>
                                @else
                                <i class="fas fa-comment-dots"></i>
                                @endif
                            </a>

                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">No contacts yet</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Chat Box -->
            <div class="col-md-8">
                <div class="card shadow border-0 rounded-4 h-100">
                    <div class="card-header fw-bold bg-white d-flex align-items-center" style="color: #3674B5; height: 60px;">
                        {{ $receiver ? '💬 Chat with ' . $receiver->name : 'Select a contact to start chatting' }}
                    </div>
                    <div class="card-body px-4 py-3" style="height: 400px; overflow-y: auto;" id="chatBox">
                        @forelse ($messages as $message)
                        <div class="d-flex mb-3 {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="p-2 px-3 rounded-pill text-white {{ $message->sender_id === auth()->id() ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $message->message }}
                            </div>
                        </div>


                        @empty
                        <p class="text-center text-muted">No messages yet. Start the conversation!</p>
                        @endforelse
                        <div id="typingIndicator" class="text-muted small ps-3 mb-2"></div>
                    </div>

                    @if ($receiver)
                    <div class="card-footer bg-light border-top-0">
                        <form id="messageForm">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">
                            <div class="input-group">
                                <input type="text" name="message" id="messageInput" class="form-control rounded-start-pill" placeholder="Type your message..." required>
                                <button type="submit" class="btn btn-primary rounded-end-pill px-4" style="background-color: #3674B5;">Send</button>
                            </div>
                        </form>

                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- FontAwesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</x-layouts.app>
@if ($receiver)
<script>
    const chatBox = document.getElementById('chatBox');
    const form = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const receiverId = "{{ $receiver->id }}";
    const authId = JSON.parse('{{ auth()->id() }}');
    const typingIndicator = document.getElementById('typingIndicator');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const message = messageInput.value;

        fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    receiver_id: receiverId
                })
            })
            .then(res => res.json())
            .then(data => {
                messageInput.value = '';
                appendMessage(data.message, true);
            });
    });

    function appendMessage(message, isSender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'd-flex mb-3 ' + (isSender ? 'justify-content-end' : 'justify-content-start');

        const bubble = document.createElement('div');
        bubble.className = 'p-2 px-3 rounded-pill text-white ' + (isSender ? 'bg-primary' : 'bg-secondary');
        bubble.textContent = message;

        messageDiv.appendChild(bubble);
        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Poll for new messages every 3 seconds
    setInterval(() => {
        fetch(`/chat/fetch/{{ $receiver->id }}`)
            .then(res => res.json())
            .then(data => {
                chatBox.innerHTML = '';
                data.forEach(msg => {
                    appendMessage(msg.message, msg.sender_id == authId);
                });
            });
    }, 3000);

    // Typing logic
    let typingTimeout;
    messageInput.addEventListener('input', () => {
        clearTimeout(typingTimeout);

        fetch("{{ route('chat.typing') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                receiver_id: receiverId
            })
        });

        // Prevent flooding the server
        typingTimeout = setTimeout(() => {}, 1000);
    });

    // Poll typing status every 1s
    setInterval(() => {
        fetch(`/chat/typing-status/{{ $receiver->id }}`)
            .then(res => res.json())
            .then(data => {
                typingIndicator.innerHTML = data.typing ? '<em>Typing...</em>' : '';
            });
    }, 1000);
</script>
@endif