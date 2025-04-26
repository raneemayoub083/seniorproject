<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762; text-align:center; text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Assigned&nbsp;Exams&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Assigned&nbsp;Exams&nbsp;</span>
            </button>
        </p>

        <div class="table-responsive mt-4">
            <table id="examsTable" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Title</th>
                        <th>Section</th>
                        <th>Subject</th>
                        <th>Grades</th>
                        <th>Start Date</th>
                        <th>Upload Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                    <tr>
                        <td>{{ $exam->event->title }}</td>
                        <td>{{ $exam->sectionSubjectTeacher->section->grade->name ?? '' }} {{ $exam->sectionSubjectTeacher->section->name ?? '' }}</td>
                        <td>{{ $exam->sectionSubjectTeacher->subject->name ?? '' }}</td>
                        <td class="text-center">
                            @if($exam->has_grades)
                            <span class="badge bg-success">Submitted</span>
                            @else
                            <a href="{{ route('teacherdash.exams.grades.form', $exam->id) }}"
                                class="btn btn-sm text-white"
                                style="background-color: #3674B5; font-weight: bold; border-radius: 6px;">
                                Upload Grades
                            </a>


                            @endif
                        </td>

                        <td>{{ \Carbon\Carbon::parse($exam->event->start)->format('d M Y, h:i A') }}</td>

                        <td>
                            @if($exam->exam_document_path)
                            <span class="badge bg-success">Uploaded</span>
                            @else
                            <span class="badge bg-warning text-dark">Pending Upload</span>
                            @endif
                        </td>
                        <td>
                            @if(!$exam->exam_document_path)
                            <!-- Upload Form -->
                            <form action="{{ route('teacher.exams.upload', $exam->id) }}" method="POST" enctype="multipart/form-data" class="uploadExamForm">
                                @csrf
                                <input type="file" name="exam_document" class="form-control form-control-sm mb-2" required>
                                <button type="submit" class="btn btn-primary btn-sm w-100">Upload</button>
                            </form>
                            @else
                            <a href="{{ Storage::url($exam->exam_document_path) }}" class="btn btn-success btn-sm w-100" target="_blank">
                                View Uploaded Exam
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#examsTable').DataTable();

            // Handle the upload form inside the table
            $('.uploadExamForm').on('submit', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(async response => {
                        if (!response.ok) {
                            const errorData = await response.json();
                            throw errorData;
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Uploaded!',
                            text: data.message || 'Document uploaded successfully!',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    })
                    .catch(async (error) => {
                        console.error('Upload error:', error);

                        let errorMessage = 'Failed to upload document. Please try again.';

                        // If the error is a Response object
                        if (error.message) {
                            errorMessage = error.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                        });
                    });
            });
        });
    </script>
</x-layouts.app>