<x-layouts.app>
    <div class="container">
        <p class="mt-4 text-center" style="color:#729762; text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Upload&nbsp;Grades&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Upload&nbsp;Grades&nbsp;</span>
            </button>
        </p>

        <div class="mt-4 mb-2">
            <h4 style="color: #3674B5;">
                Exam: {{ $exam->event->title }} |
                Subject: {{ $exam->sectionSubjectTeacher->subject->name }} |
                Section: {{ $exam->sectionSubjectTeacher->section->grade->name }} {{ $exam->sectionSubjectTeacher->section->name }}
            </h4>
        </div>

        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
        </script>
        @endif

        <form id="gradesForm" action="{{ route('teacherdash.exams.grades.submit', $exam->id) }}" method="POST">
            @csrf
            <div class="table-responsive mt-3">
                <table id="gradesTable" class="table table-striped table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Student Name</th>
                            <th>Grade (out of 100)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>{{ $student->application->first_name }} {{ $student->application->last_name }}</td>
                            <td>

                                <input type="number"
                                    name="grades[{{ $student->id }}]"
                                    class="form-control"
                                    min="0" max="100" step="0.01"
                                    placeholder="Enter grade"
                                    value="{{ $student->pivot->grade ?? '' }}"
                                    required>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">No students found in this section.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-end">
                <button type="button" id="confirmSubmit" class="btn mt-3 px-4 py-2 shadow"
                    style="background-color: #3674B5; color: white; font-weight: bold;">
                    Submit Grades
                </button>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#gradesTable').DataTable();

            $('#confirmSubmit').on('click', function(e) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to submit all grades now?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3674B5',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show success toast first
                        Swal.fire({
                            icon: 'success',
                            title: 'Grades Submitted!',
                            text: 'All grades will be saved.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Wait a short moment before submitting form to let alert show
                        setTimeout(() => {
                            $('#gradesForm').submit();
                        }, 1600); // slightly longer than alert timer
                    }
                });
            });
        });
    </script>

</x-layouts.app>