<x-layouts.app>
    <div class="container">
        <p class="mt-4 text-center" style="color:#729762;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;My&nbsp;Attendance&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;My&nbsp;Attendance&nbsp;</span>
            </button>
        </p>

        <div id="calendar" class="mt-4"></div>
    </div>

    @push('scripts')
    <!-- FullCalendar + SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            // ✅ Create and expose calendar globally
            window.attendanceCalendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                events: '{{ route("student.attendance.events", $section->id) }}',

                dateClick: function(info) {
                    const clickedDate = info.dateStr;
                    const allEvents = window.attendanceCalendar.getEvents();

                    const matches = allEvents.filter(e => {
                        const eventDate = new Date(e.start).toLocaleDateString('en-CA'); // "YYYY-MM-DD"
                        console.log('🔍 Comparing:', eventDate, '==', clickedDate);
                        return eventDate === clickedDate;
                    });

                    if (matches.length > 0) {
                        Swal.fire({
                            title: clickedDate,
                            html: matches.map(e => `<div>${e.title}</div>`).join(""),
                            icon: 'info',
                        });
                    } else {
                        Swal.fire({
                            title: clickedDate,
                            text: "Attendance not set for this day.",
                            icon: 'info',
                        });
                    }
                }
            });

            window.attendanceCalendar.render();
        });

        // ✅ Replace emojis with "Present" or "Absent"
        function convertEmojiToText(title) {
            return title
                .replace(/✅/g, 'Present')
                .replace(/❌/g, 'Absent');
        }
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const synth = window.speechSynthesis;
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (!SpeechRecognition || !synth) {
                alert("Voice features not supported in your browser.");
                return;
            }

            const recognition = new SpeechRecognition();
            recognition.lang = 'en-US';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            let step = 0;
            let dateParts = {
                month: '',
                day: '',
                year: ''
            };

            function speak(text, callback) {
                if (synth.speaking) synth.cancel();
                const utter = new SpeechSynthesisUtterance(text);
                utter.lang = 'en-US';
                utter.rate = 1;
                if (callback) utter.onend = () => setTimeout(callback, 500);
                synth.speak(utter);
            }

            function listen(callback) {
                recognition.start();
                recognition.onresult = (event) => {
                    const result = event.results[0][0].transcript.toLowerCase().replace(/[.,!?]/g, '').trim();
                    console.log("🎤 Heard:", result);
                    callback(result);
                };
                recognition.onerror = () => {
                    speak("I didn’t catch that. Please repeat.", () => listen(callback));
                };
            }

            function askForDatePart() {
                if (step === 0) speak("Which month?", () => listen(storeMonth));
                else if (step === 1) speak("Which day of the month?", () => listen(storeDay));
                else if (step === 2) speak("Which year?", () => listen(storeYear));
            }

            function storeMonth(input) {
                const months = {
                    january: "01",
                    february: "02",
                    march: "03",
                    april: "04",
                    may: "05",
                    june: "06",
                    july: "07",
                    august: "08",
                    september: "09",
                    october: "10",
                    november: "11",
                    december: "12"
                };
                const monthKey = input.toLowerCase();
                if (!months[monthKey]) {
                    speak("Please say a valid month like April or September.", askForDatePart);
                    return;
                }
                dateParts.month = months[monthKey];
                step++;
                askForDatePart();
            }

            function storeDay(input) {
                const day = input.match(/\d+/)?.[0];
                if (!day || isNaN(day) || day < 1 || day > 31) {
                    speak("Say a valid day between 1 and 31.", askForDatePart);
                    return;
                }
                dateParts.day = day.padStart(2, '0');
                step++;
                askForDatePart();
            }

            function storeYear(input) {
                const year = input.match(/\d{4}/)?.[0];
                if (!year) {
                    speak("Please say a valid four-digit year like 2025.", askForDatePart);
                    return;
                }
                dateParts.year = year;
                checkAttendance();
            }

            function checkAttendance() {
                const targetDate = `${dateParts.year}-${dateParts.month}-${dateParts.day}`;
                console.log("🔍 Final date string:", targetDate);

                const calendar = window.attendanceCalendar;
                const events = calendar?.getEvents?.() || [];
                console.log("📦 Loaded Events:", events);

                const matched = events.filter(e =>
                    new Date(e.start).toLocaleDateString('en-CA') === targetDate
                );

                if (matched.length === 0) {
                    speak(`No attendance found for ${dateParts.month}/${dateParts.day}/${dateParts.year}.`, askRepeatOrExit);
                    return;
                }

                const phrases = [];
                matched.forEach(event => {
                    const chunks = event.title.split(','); // Split on commas in title
                    chunks.forEach(rawChunk => {
                        const chunk = rawChunk.trim();
                        if (chunk.startsWith('❌')) {
                            const subject = chunk.replace('❌', '').trim();
                            phrases.push(`Absent in ${subject}`);
                        } else if (chunk.startsWith('✅')) {
                            const subject = chunk.replace('✅', '').trim();
                            phrases.push(`Present in ${subject}`);
                        }
                    });
                });

                const summary = phrases.join(', ');
                speak(`Attendance for ${dateParts.month}/${dateParts.day}/${dateParts.year}: ${summary}`, askRepeatOrExit);
            }

            function askRepeatOrExit() {
                speak("Would you like to check another date or stop?", () => {
                    listen(answer => {
                        if (answer.includes("stop") || answer.includes("exit") || answer.includes("no")) {
                            speak("Goodbye.");
                        } else {
                            step = 0;
                            dateParts = {
                                month: '',
                                day: '',
                                year: ''
                            };
                            askForDatePart();
                        }
                    });
                });
            }

            // Start assistant
            setTimeout(() => {
                speak("Welcome to your attendance calendar. Let's check your attendance. I will ask for the month, day, and year.", () => {
                    step = 0;
                    dateParts = {
                        month: '',
                        day: '',
                        year: ''
                    };
                    askForDatePart();
                });
            }, 1000);
        });
    </script>



    @endpush
</x-layouts.app>