<x-layouts.app>
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:#3674B5; text-shadow:1px 1px 2px rgba(0,0,0,0.1);">Student Classes Overview</h2>
            <p class="text-muted">Select your child to view their enrolled and previous classes</p>
        </div>

        <div class="mb-4">
            <label for="studentSelect" class="form-label fw-bold" style="color:#3674B5;">Select a Student</label>
            <select id="studentSelect" class="form-select border-primary shadow-sm" style="border-color:#3674B5;">
                <option value="">-- Choose a student --</option>
                @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->application->first_name }} {{ $student->application->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div id="classesTableContainer" class="mt-4" style="display:none;">
            <div class="table-responsive shadow-sm border rounded">
                <table class="table table-striped" id="classesTable">
                    <thead style="background-color: #3674B5; color:white;">
                        <tr>
                            <th>Academic Year</th>
                            <th>Class Name</th>
                            <th>Subjects</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />

    <script>
        $(document).ready(function() {
            let table = $('#classesTable').DataTable();

            $('#studentSelect').change(function() {
                const studentId = $(this).val();
                if (!studentId) return;

                $.ajax({
                    url: `/parent/classes/${studentId}`,
                    method: 'GET',
                    success: function(res) {
                        table.clear().draw();
                        $('#classesTableContainer').fadeIn();

                        res.sections.forEach(section => {
                            let subjects = (section.grade?.subjects || []).map(sub => `
    <button class="btn btn-sm subject-btn" 
            data-subject-id="${sub.id}" 
            data-student-id="${studentId}" 
            data-section-id="${section.id}" 
            style="background-color:#3674B5; color:white; margin:2px;">
        ${sub.name}
    </button>
`).join(" ");

                            let status = section.pivot?.status ?? 'N/A';

                            table.row.add([
                                section.academic_year?.name ?? 'N/A',
                                section.grade?.name ?? 'N/A',
                                subjects,
                                `<span class="badge rounded-pill text-bg-primary" style="background-color:#3674B5;">${status}</span>`
                            ]).draw(false);
                        });
                    }
                });
            });
        });
        $(document).on('click', '.subject-btn', function() {
            const subjectId = $(this).data('subject-id');
            const studentId = $(this).data('student-id');
            const sectionId = $(this).data('section-id');

            $.ajax({
                url: `/parent/subject-info`,
                method: 'POST',
                data: {
                    subject_id: subjectId,
                    student_id: studentId,
                    section_id: sectionId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    let content = `<h5><strong>Teacher:</strong> ${res.teacher ?? 'N/A'}</h5>`;
                    if (res.exams.length > 0) {
                        content += `<h6 class="mt-3">Exams:</h6><ul>`;
                        res.exams.forEach(e => {
                            content += `<li><strong>${e.exam_title}</strong> - Grade: ${e.grade ?? 'N/A'}</li>`;
                        });

                        content += `</ul>`;
                    } else {
                        content += `<p>No exams found for this subject.</p>`;
                    }

                    $('#subjectInfo').html(content);
                    $('#subjectModal').modal('show');
                }
            });
        });
    </script>
</x-layouts.app>
<!-- Modal -->
<div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background:#3674B5;">
                <h5 class="modal-title text-white" id="subjectModalLabel">Subject Details</h5>
                <button type="button" class="btn-close" style="filter: invert(1);" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <div id="subjectInfo"></div>
            </div>
        </div>
    </div>
</div>