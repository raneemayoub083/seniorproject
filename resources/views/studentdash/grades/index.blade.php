<x-layouts.app>
    <div class="container mt-4">
        <div class="text-center mb-4">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Grades&nbsp;for&nbsp;{{ $section->grade->name }}&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Grades&nbsp;for&nbsp;{{ $section->grade->name }}&nbsp;</span>
            </button>
        </div>

        <div class="table-responsive">
            <table id="gradesTable" class="table table-hover table-striped table-bordered align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Subject</th>
                        <th>Exam title</th>
                        <th>Grade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades as $grade)

                    <tr>
                        <td>{{ $grade->subject_name }}</td>
                        <td>{{ $grade->exam_title ?? '—' }}</td>
                        <td>
                            @if(is_null($grade->grade))
                            <span class="text-muted">—</span>
                            @elseif($grade->grade < 50)
                                <span class="text-danger fw-bold">{{ $grade->grade }}/100</span>
                                @else
                                <span class="text-success fw-bold">{{ $grade->grade }}/100</span>
                                @endif
                        </td>
                        <td>
                            @if(is_null($grade->grade))
                            <span class="badge bg-secondary">Pending</span>
                            @elseif($grade->grade < 50)
                                <span class="badge bg-danger">Failed</span>
                                @else
                                <span class="badge bg-success">Passed</span>
                                @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No grades available yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .text-success {
            color: rgb(0, 123, 255) !important;
        }

        .bg-success {
            background-color: rgb(0, 123, 255) !important;
        }
    </style>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(() => {
            $('#gradesTable').DataTable({
                order: [
                    [0, 'asc']
                ],
                pageLength: 10,
                language: {
                    emptyTable: "No grades yet",
                    search: "🔍 Search:",
                    paginate: {
                        previous: "<",
                        next: ">"
                    }
                }
            });
        });
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const synth = window.speechSynthesis;
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (!SpeechRecognition || !synth) {
                alert("Speech features are not supported in your browser.");
                return;
            }

            const recognition = new SpeechRecognition();
            recognition.lang = 'en-US';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            let active = true;

            function speak(text, callbackAfter) {
                if (synth.speaking) synth.cancel();
                const utter = new SpeechSynthesisUtterance(text);
                utter.lang = 'en-US';
                utter.rate = 1;
                if (callbackAfter) {
                    utter.onend = () => setTimeout(callbackAfter, 500);
                }
                synth.speak(utter);
            }

            function normalizeText(text) {
                if (!text) return "";
                const numberWords = {
                    "one": "1",
                    "two": "2",
                    "three": "3",
                    "four": "4",
                    "five": "5",
                    "six": "6",
                    "seven": "7",
                    "eight": "8",
                    "nine": "9",
                    "ten": "10"
                };
                let normalized = text.toLowerCase().trim();
                Object.keys(numberWords).forEach(word => {
                    const regex = new RegExp(`\\b${word}\\b`, 'g');
                    normalized = normalized.replace(regex, numberWords[word]);
                });
                return normalized
                    .replace(/[^\w\s]/gi, '')
                    .replace(/\s+/g, ' ')
                    .replace(/\b(the|for|of|my|to|in|on|at|a|an)\b/g, '')
                    .trim();
            }

            function readGradesStepByStep(callback) {
                const rows = document.querySelectorAll('#gradesTable tbody tr');
                if (!rows.length || rows[0].textContent.includes("No grades available")) {
                    speak("There are no grades available yet.", callback);
                    return;
                }

                let index = 0;
                const readNext = () => {
                    if (index >= rows.length || !active) {
                        speak("That was the last grade.", callback);
                        return;
                    }

                    const cells = rows[index].querySelectorAll('td');
                    if (cells.length === 4) {
                        const subject = cells[0].innerText.trim();
                        const exam = cells[1].innerText.trim();
                        const grade = cells[2].innerText.trim();
                        const status = cells[3].innerText.trim();
                        const message = `Subject: ${subject}. Exam: ${exam}. Grade: ${grade}. Status: ${status}.`;

                        const utterance = new SpeechSynthesisUtterance(message);
                        utterance.onend = () => {
                            index++;
                            readNext();
                        };
                        synth.speak(utterance);
                    } else {
                        index++;
                        readNext();
                    }
                };

                readNext();
            }

            function respondToExamQuery(spokenText, callback) {
                let examTitle = normalizeText(spokenText);
                const rows = document.querySelectorAll('#gradesTable tbody tr');
                let found = false;

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const storedExam = normalizeText(cells[1].innerText);
                    if (storedExam.includes(examTitle) || examTitle.includes(storedExam)) {
                        const subject = cells[0].innerText.trim();
                        const grade = cells[2].innerText.trim();
                        const status = cells[3].innerText.trim();
                        const message = `For exam ${cells[1].innerText}, in subject ${subject}, you got ${grade}. Status: ${status}.`;
                        speak(message, callback);
                        found = true;
                    }
                });

                if (!found) {
                    speak("I couldn't find that exam. Can you say the title again?", () => {
                        listenForCommand();
                    });
                }
            }

            function handleCommand(spokenText) {
                spokenText = spokenText.toLowerCase();
                console.log("🧠 Processing:", spokenText);
                if (transcript.includes("enter my classes")) {
                    speak("Entering your classes.", () => {
                        window.location.href = "{{ route('studentdash.classes') }}";
                    });
                } else if (transcript.includes("enter my active class")) {
                    speak("Opening your active class.", () => {
                        window.location.href = "{{ route('studentdash.activeclass') }}";
                    });
                }
                if (spokenText.includes("stop") || spokenText.includes("exit") || spokenText.includes("goodbye")) {
                    active = false;
                    speak("Okay, stopping now. Have a great day!");
                    return;
                }

                if (spokenText.includes("all grades") || spokenText.includes("read all")) {
                    readGradesStepByStep(() => {
                        if (active) {
                            speak("What would you like to do next?", () => {
                                listenForCommand();
                            });
                        }
                    });
                } else if (spokenText.includes("grade") || spokenText.includes("exam")) {
                    respondToExamQuery(spokenText, () => {
                        if (active) {
                            speak("Do you want to hear another one?", () => {
                                listenForCommand();
                            });
                        }
                    });
                } else {
                    speak("Sorry, I didn’t understand. You can say things like 'read all grades' or 'math exam three'.", () => {
                        listenForCommand();
                    });
                }
            }

            function listenForCommand() {
                if (!active) return;

                recognition.start();
                recognition.onresult = (event) => {
                    const transcript = event.results[0][0].transcript.toLowerCase();
                    handleCommand(transcript);
                };

                recognition.onerror = (event) => {
                    console.error("🎤 Recognition error:", event);
                    speak("Sorry, I couldn't hear that. Please say it again.", () => {
                        listenForCommand();
                    });
                };
            }

            // Start the loop
            setTimeout(() => {
                speak("Welcome! You can ask me to read all grades or ask about a specific exam. What would you like to do?", () => {
                    listenForCommand();
                });
            }, 1000);
        });
    </script>



    <style>
        .table-hover tbody tr:hover {
            background-color: #f0f9ff;
        }
    </style>
</x-layouts.app>