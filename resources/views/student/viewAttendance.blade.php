<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Student&nbsp;Attendance&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Student&nbsp;Attendance&nbsp;</span>
            </button>
        </p>

        <h5 class="text-center mt-4">
            Attendance for {{ $student->application?->first_name }} {{ $student->application?->last_name }} — Section: {{ $section->grade->name }} / {{ $section->academicYear->name }}
        </h5>

        <div class="row justify-content-center my-4">
            <div class="col-md-6">
                <label for="subjectSelect">Select Subject</label>
                <select id="subjectSelect" class="form-select">
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ $firstSubject && $subject->id == $firstSubject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table id="attendanceTable" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Subject</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</td>
                        <td>
                            @if($attendance->status === 'present')
                            ✅ Present
                            @else
                            ❌ Absent
                            @endif
                        </td>
                        <td>{{ $attendance->subject->name ?? 'Unknown' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            const studentId = "{{ $student->id }}";
            const sectionId = "{{ $section->id }}";
            const table = $('#attendanceTable').DataTable({
                pagingType: "full_numbers",
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search attendance...",
                }
            });

            $('#subjectSelect').on('change', function() {
                const subjectId = $(this).val();
                const url = `/student/${studentId}/attendance/${sectionId}/subject/${subjectId}`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        table.clear();

                        if (data.length === 0) {
                            table.row.add(["No attendance records found.", "—", "—"]).draw();
                        } else {
                            data.forEach(item => {
                                table.row.add([
                                    item.date,
                                    item.status === 'present' ? '✅ Present' : '❌ Absent',
                                    item.subject?.name ?? 'Unknown'
                                ]);
                            });
                            table.draw();
                        }
                    });
            });
        });
    </script>
</x-layouts.app>