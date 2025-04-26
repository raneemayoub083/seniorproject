<x-layouts.app>
    <div class="container py-4">
        <div class="text-center mb-4">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Lessons&nbsp;for&nbsp;{{ $subjectName }}&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Lessons&nbsp;for&nbsp;{{ $subjectName }}&nbsp;</span>
            </button>
        </div>

        <div class="text-center mb-3">
            <button id="startReadingBtn" onclick="triggerReadingManually()" class="view" style="display:none">📖 Start Reading</button>
        </div>

        <p><strong>Teacher:</strong> {{ $teacherName }}</p>

        <div class="table-responsive">
            <table id="lessonsTable" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Lesson Name</th>
                        <th>Description</th>
                        <th>Video</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lessons as $lesson)
                    <tr>
                        <td>{{ $lesson->title }}</td>
                        <td>{{ $lesson->description }}</td>
                        <td>
                            @if($lesson->video)
                            <a href="{{ Storage::url($lesson->video) }}" target="_blank">View Video</a>
                            @else
                            N/A
                            @endif
                        </td>
                        <td>
                            @if($lesson->pdf)
                            <a href="{{ Storage::url($lesson->pdf) }}" target="_blank">View PDF</a><br>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2 translateBrailleBtn"
                                data-url="{{ Storage::url($lesson->pdf) }}"
                                data-title="{{ $lesson->title }}">
                                ♿ Translate to Braille
                            </button>
                            @else
                            N/A
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No lessons available for this subject in this section.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Voice Control Buttons -->
        <div style="position: fixed; bottom: 20px; left: 300px; z-index: 9999;">
            <button onclick="pauseReading()" style="margin-right: px;">⏸️ Pause</button>
            <button onclick="resumeReading()" style="margin-right: 5px;">▶️ Resume</button>
            <button onclick="stopReading()">⏹️ Stop</button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    <script>
        const synth = window.speechSynthesis;
        let currentUtterance = null;
        let userHasInteracted = false;

        // Expose speakText globally for debug
        window.speakText = speakText;

        // Load voices on change
        speechSynthesis.onvoiceschanged = () => {
            console.log("✅ Voices loaded:", speechSynthesis.getVoices());
        };

        function waitForVoices(callback) {
            let voices = synth.getVoices();
            if (voices.length) return callback(voices);
            const interval = setInterval(() => {
                voices = synth.getVoices();
                if (voices.length) {
                    clearInterval(interval);
                    callback(voices);
                }
            }, 100);
        }

        function speakText(text) {
            if (!text || !synth) return console.warn("🚫 No text or speech synthesis not supported.");
            if (!userHasInteracted) return console.warn("🛑 Speech blocked: no user interaction.");

            if (synth.speaking || synth.pending) {
                console.warn("⏳ Already speaking or pending, cancelling...");
                synth.cancel();
                setTimeout(() => speakText(text), 500);
                return;
            }

            try {
                synth.cancel();
                setTimeout(() => {
                    const toSpeak = text.length > 300 ? text.slice(0, 300) + ' ...' : text;
                    console.log("🗣️ Speaking text now:", toSpeak);

                    const utterance = new SpeechSynthesisUtterance(toSpeak);
                    utterance.lang = 'en-US';
                    utterance.onstart = () => console.log("🔊 Voice speaking started...");
                    utterance.onend = () => console.log("✅ Voice finished.");
                    utterance.onerror = (e) => {
                        console.error("❌ Speech failed:", e.error || e);
                        Swal.fire('Speech Error', 'Browser failed to speak.', 'error');
                    };

                    waitForVoices((voices) => {
                        let selectedVoice = voices.find(v => v.name === 'Google US English') ||
                            voices.find(v => v.name.includes('Microsoft David')) ||
                            voices.find(v => v.lang.startsWith('en'));

                        if (!selectedVoice && voices.length) {
                            selectedVoice = voices[0];
                        }

                        if (selectedVoice) {
                            utterance.voice = selectedVoice;
                            console.log("✅ Using voice:", selectedVoice.name);
                        } else {
                            console.warn("⚠️ No suitable voice found.");
                        }

                        synth.speak(utterance);
                    });
                }, 500);
            } catch (err) {
                console.error("❌ Speech Exception:", err);
            }
        }

        function speakAndAlert(title, message, icon = 'info') {
            Swal.fire(title, message, icon);
            speakText(`${title}. ${message}`);
        }

        window.addEventListener('click', () => {
            if (!userHasInteracted) {
                userHasInteracted = true;
                console.log("👆 User interaction enabled speech");
            }
        });
    </script>

    <script>
        $(document).ready(() => {
            $('#lessonsTable').DataTable();

            $('.translateBrailleBtn').on('click', function() {
                const fileUrl = $(this).data('url');
                const title = $(this).data('title');

                Swal.fire({
                    title: 'Generating Braille...',
                    html: 'Please wait while we convert this lesson to Braille format.',
                    didOpen: () => Swal.showLoading(),
                    allowOutsideClick: false
                });
                speakText('Generating Braille. Please wait while we convert this lesson to Braille format.');

                $.ajax({
                    url: "{{ route('translate-to-braille') }}",
                    method: "POST",
                    data: {
                        fileUrl: fileUrl,
                        lessonTitle: title,
                        _token: "{{ csrf_token() }}"
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        const blob = new Blob([data], {
                            type: "text/plain"
                        });
                        const downloadUrl = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = downloadUrl;
                        a.download = `${title.trim().replace(/\s+/g, '_').toLowerCase()}_braille.txt`;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);

                        Swal.close();
                        speakAndAlert('✅ Success', 'Braille file downloaded.', 'success');
                    },
                    error: function(xhr) {
                        let message = 'Something went wrong.';
                        if (xhr.responseJSON?.error) {
                            message = xhr.responseJSON.error;
                        }
                        speakAndAlert('Error', message, 'error');
                    }
                });
            });
        });

        function pauseReading() {
            if (synth.speaking && !synth.paused) {
                synth.pause();
                speakAndAlert('Paused', 'Reading paused.', 'success');
            }
        }

        function resumeReading() {
            if (synth.paused) {
                synth.resume();
                speakAndAlert('Resumed', 'Reading resumed.', 'success');
            }
        }

        function stopReading() {
            if (synth.speaking || synth.paused) {
                synth.cancel();
                speakAndAlert('Stopped', 'Reading stopped.', 'success');
            }
        }

        function triggerReadingManually() {
            userHasInteracted = true;
            speakAndAlert('Voice Ready', 'You can now use voice commands to read lessons.', 'success');
        }

        async function readDocumentUnified(title) {
            const rows = document.querySelectorAll('#lessonsTable tbody tr');
            for (const row of rows) {
                const lessonName = row.cells[0]?.textContent.trim().toLowerCase();
                if (lessonName.includes(title)) {
                    const fileLink = row.cells[3]?.querySelector('a')?.href;
                    if (!fileLink) break;

                    const extension = fileLink.split('.').pop().toLowerCase();
                    let text = '';

                    try {
                        if (extension === 'pdf') {
                            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
                            const pdf = await pdfjsLib.getDocument(fileLink).promise;
                            for (let i = 1; i <= pdf.numPages; i++) {
                                const page = await pdf.getPage(i);
                                const content = await page.getTextContent();
                                text += content.items.map(item => item.str).join(' ') + '\n';
                            }
                        } else if (extension === 'txt') {
                            const res = await fetch(fileLink);
                            text = await res.text();
                        } else if (extension === 'docx') {
                            const res = await fetch(fileLink);
                            const buffer = await res.arrayBuffer();
                            const result = await mammoth.extractRawText({
                                arrayBuffer: buffer
                            });
                            text = result.value;
                        } else {
                            speakAndAlert('Unsupported', `File type .${extension} not supported yet.`, 'warning');
                            return;
                        }

                        if (text.trim()) {
                            console.log("✅ Extracted PDF text:", text.slice(0, 300));
                            console.log("🚀 Calling speakText with extracted content...");
                            speakText(text);
                        } else {
                            console.warn("⚠️ PDF.js could not extract readable content.");
                            speakAndAlert('Empty', 'No readable content found in the file.', 'info');
                        }
                    } catch (err) {
                        speakAndAlert('Error', 'Failed to read the document.', 'error');
                        console.error('Reading error:', err);
                    }

                    return;
                }
            }

            speakAndAlert('Not Found', `Lesson "${title}" not found.`, 'error');
        }

        function openLessonLink(title, type) {
            const rows = document.querySelectorAll('#lessonsTable tbody tr');
            for (const row of rows) {
                const lessonName = row.cells[0]?.textContent.trim().toLowerCase();
                if (lessonName.includes(title)) {
                    const linkCell = type === 'video' ? row.cells[2] : row.cells[3];
                    const link = linkCell.querySelector('a');
                    if (link) {
                        window.open(link.href, '_blank');
                        return;
                    }
                }
            }
            console.warn(`Lesson "${title}" not found.`);
        }

        window.addEventListener('DOMContentLoaded', () => {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) {
                alert("Speech recognition not supported.");
                return;
            }

            const recognition = new SpeechRecognition();
            recognition.continuous = true;
            recognition.lang = 'en-US';
            recognition.start();

            recognition.onresult = (event) => {
                const transcript = event.results[event.resultIndex][0].transcript.trim().toLowerCase();
                console.log('🎤 Heard:', transcript);

                const has = (words) => words.every(w => transcript.includes(w));

                if (has(['enter', 'dashboard'])) {
                    window.location.href = "{{ route('studentdash.dashboard') }}";
                } else if (has(['enter', 'classes'])) {
                    window.location.href = "{{ route('studentdash.classes') }}";
                } else if (has(['enter', 'active', 'class'])) {
                    window.location.href = "{{ route('studentdash.activeclass') }}";
                } else if (transcript.includes('start reading')) {
                    document.getElementById('startReadingBtn')?.click();
                } else if (transcript.includes('enter the video of')) {
                    let title = transcript.split('enter the video of')[1]?.trim();
                    if (title) {
                        title = title.replace(/[.?!]$/, '').trim();
                        openLessonLink(title, 'video');
                    }
                } else if (transcript.includes('read the file of')) {
                    document.getElementById('startReadingBtn')?.click();
                    let title = transcript.split('read the file of')[1]?.trim();
                    title = title.replace(/[.?!]$/, '').trim();
                    if (title) {
                        speakAndAlert('Reading', `Reading "${title}"...`, 'info');
                        setTimeout(() => readDocumentUnified(title), 500);
                    }
                } else if (transcript.includes('pause')) {
                    pauseReading();
                } else if (transcript.includes('resume') || transcript.includes('continue')) {
                    resumeReading();
                } else if (transcript.includes('stop')) {
                    stopReading();
                }
            };

            recognition.onerror = (event) => {
                console.error('Speech recognition error:', event);
            };
        });

        window.addEventListener('click', () => {
            userHasInteracted = true;
        });
    </script>



</x-layouts.app>
@include('studentdash.sidebar')