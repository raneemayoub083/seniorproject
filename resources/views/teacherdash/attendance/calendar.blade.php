<x-layouts.app>

    <div class="container">
        <p class="mt-4 text-center" style="color:#729762; text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Attendance&nbsp;Calendar&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Attendance&nbsp;Calendar&nbsp;</span>
            </button>
        </p>

        <!-- Dropdown to select section + subject -->
        <div class="row mb-4">
            <div class="col-md-12">
                <label for="assignmentSelect" class="form-label">Select Class & Subject:</label>
                <select class="form-select" id="assignmentSelect">
                    <option disabled selected>Select Class and Subject</option>
                    @foreach($assignments as $a)
                    <option
                        value="{{ $a->id }}"
                        data-section="{{ $a->section_id }}"
                        data-subject="{{ $a->subject_id }}">
                        {{ $a->section->grade->name }} - {{ $a->section->name }} ({{ $a->subject->name }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Calendar -->
        <div class="mt-4" id="calendar"></div>
    </div>

    <!-- Attendance Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="attendanceForm" method="POST">
                @csrf
                <input type="hidden" name="date" id="attendance-date">
                <input type="hidden" name="section_id" id="section-id">
                <input type="hidden" name="subject_id" id="subject-id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Take Attendance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="attendance-body">
                        <!-- Loaded dynamically -->
                    </div>
                    <div class="modal-footer">

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Save Attendance</button>
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        let calendar;

        $('#assignmentSelect').on('change', function() {
            const sectionId = $(this).find(':selected').data('section');
            const subjectId = $(this).find(':selected').data('subject');

            $('#section-id').val(sectionId);
            $('#subject-id').val(subjectId);

            if (calendar) calendar.destroy();

            calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                height: 'auto',
                eventColor: '#28a745',
                events: `/teacher/attendance/events?section_id=${sectionId}&subject_id=${subjectId}`,
                dateClick: function(info) {
                    const clickedDate = new Date(info.dateStr);
                    const now = new Date();

                    const timeDiff = now - clickedDate; // in milliseconds
                    const hoursDiff = timeDiff / (1000 * 60 * 60); // convert to hours

                    if (hoursDiff > 24) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Too Late',
                            text: 'You cannot update attendance for past dates beyond 24 hours.',
                        });
                        return;
                    }

                    // Continue loading modal
                    $('#attendance-date').val(info.dateStr);
                    $.get(`/teacher/attendance/students`, {
                        date: info.dateStr,
                        section_id: $('#section-id').val(),
                        subject_id: $('#subject-id').val()
                    }, function(html) {
                        $('#attendance-body').html(html);
                        $('#attendanceModal').modal('show');
                    });
                }

            });

            calendar.render();
        });

        $('#attendanceForm').submit(function(e) {
            e.preventDefault();

            $('#attendanceForm input[type=checkbox]').each(function() {
                if (!$(this).is(':checked')) {
                    $(this).val('absent').prop('checked', true);
                }
            });

            $.post("{{ route('attendance.store') }}", $(this).serialize(), function(res) {
                $('#attendanceModal').modal('hide');
                calendar.refetchEvents();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
    </script>
</x-layouts.app>