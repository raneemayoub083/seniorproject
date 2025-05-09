<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Student&nbsp;Grades&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Student&nbsp;Grades&nbsp;</span>
            </button>
        </p>

        <h5 class="mt-4 text-center">
            Grades for {{ $student->application?->first_name }} {{ $student->application?->last_name }} — Section: {{ $section->grade->name }} / {{ $section->academicYear->name }}
        </h5>

        <div class="row my-4 justify-content-center">
            <div class="col-md-6">
                <label for="subjectSelect" class="form-label">Select Subject</label>
                <select class="form-select" id="subjectSelect">
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ $firstSubject && $subject->id == $firstSubject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table id="gradesTable" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Exam Title</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grade)
                    <tr>
                        <td>{{ $grade->exam_title }}</td>
                        <td>{{ $grade->grade }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
                    const studentId = "{{ $student->id }}";
                    const sectionId = "{{ $section->id }}";

                    const table = $('#gradesTable').DataTable({
                        pagingType: "full_numbers",
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        responsive: true,
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search exams...",
                        }
                    });

                    $('#subjectSelect').on('change', function() {
                        const subjectId = $(this).val();
                        const studentId = "{{ $student->id }}";
                        const sectionId = "{{ $section->id }}";
                        const url = `/student/${studentId}/grades/${sectionId}/subject/${subjectId}`; // ✅ full dynamic URL

                        fetch(url)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                table.clear();

                                if (data.length === 0) {
                                    table.row.add(['No exams found.', '—']).draw();
                                } else {
                                    data.forEach(row => {
                                        table.row.add([row.exam_title, row.grade]);
                                    });
                                    table.draw();
                                }
                            })
                            .catch(error => {
                                console.error('Fetch error:', error);
                                Swal.fire('Error', 'Could not load grades. Please check console.', 'error');
                            });
                    });
                });
    </script>
</x-layouts.app>