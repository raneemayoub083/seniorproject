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
                            <th>Status</th>
                            <th>Subjects</th>

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
                            const subjects = (section.grade?.subjects || []).map(sub => `
                            <button class="btn btn-sm subject-btn" 
                                    data-subject-id="${sub.id}" 
                                    data-student-id="${studentId}" 
                                    data-section-id="${section.id}" 
                                    style="background-color:#3674B5; color:white; margin:2px;">
                                ${sub.name}
                            </button>
                        `).join(" ");

                            const status = section.pivot?.status ?? 'N/A';
                            const finalGrade = section.pivot?.final_grade !== null ? section.pivot.final_grade : 'N/A';

                            let statusBadge = `<span class="badge rounded-pill bg-secondary">${status}</span>`;
                            if (status === 'pass') statusBadge = `<span class="badge rounded-pill bg-success">✅ Pass</span>`;
                            if (status === 'fail') statusBadge = `<span class="badge rounded-pill bg-danger">❌ Fail</span>`;

                            const reportCardButton = `
                            <a href="/parent/report-card/${studentId}/${section.id}" 
                               class="btn btn-sm btn-outline-primary mt-2">
                                📄 Report Card
                            </a>
                        `;

                            let reEnrollBtn = '';
                            if (status === 'pass' && res.nextAcademicYear) {
                                reEnrollBtn = `
        <button class="btn btn-sm btn-success enroll-btn mt-2"
                data-student-id="${studentId}"
                data-section-id="${section.id}">
            ➕ Promote to Next Grade
        </button>
    `;
                            }

                            table.row.add([
                                section.academic_year?.name ?? 'N/A',
                                section.grade?.name ?? 'N/A',
                                `
                                <div class="d-flex flex-column align-items-center">
                                    ${statusBadge}
                                    <small class="text-muted">Final Grade: ${finalGrade}</small>
                                    ${reportCardButton}
                                    ${reEnrollBtn}
                                </div>
                            `,
                                subjects
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
        $(document).on('click', '.enroll-btn', function(e) {
            e.preventDefault();

            const button = $(this);
            const studentId = button.data('student-id');
            const sectionId = button.data('section-id');

            // Disable to prevent double click
            button.prop('disabled', true).text('⏳ Processing...');

            $.ajax({
                url: `/parent/enroll-next/${studentId}/${sectionId}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Enrolled!',
                        text: response.message || 'Student promoted to next grade successfully.',
                        confirmButtonColor: '#3674B5'
                    });
                    button.text('✅ Promoted');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON?.message || 'Failed to enroll student. Try again later.',
                        confirmButtonColor: '#d33'
                    });
                    button.prop('disabled', false).text('➕ Promote to Next Grade');
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