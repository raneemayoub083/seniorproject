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
                    </div>

                    @if ($receiver)
                    <div class="card-footer bg-light border-top-0">
                        <form method="POST" action="{{ route('chat.send') }}">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">
                            <div class="input-group">
                                <input type="text" name="message" class="form-control rounded-start-pill" placeholder="Type your message..." required>
                                <button class="btn btn-primary rounded-end-pill px-4" style="background-color: #3674B5;">Send</button>
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