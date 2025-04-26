<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Subjects&nbsp;for&nbsp;Section:&nbsp;{{ $section->name }}&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Subjects&nbsp;for&nbsp;Section:&nbsp;{{ $section->name }}&nbsp;</span>
            </button>
        </p>

        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('academic_year.sections', ['id' => $section->academic_year_id]) }}" class="btn btn-success">Back to Sections</a>
        </div>

        <div class="table-responsive">
            <table id="subjects" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Subject Name</th>
                        <th>Assign Teacher</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr>
                        <td>{{ $subject->name }}</td>
                        <td>
                            <form action="{{ route('assign.teacher') }}" method="POST" class="assign-teacher-form">
                                @csrf
                                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                <input type="hidden" name="section_id" value="{{ $section->id }}">
                                <select name="teacher_id" class="form-control">
                                    @foreach($teachers as $teacher)
                                    @if($teacher->subjects->contains($subject->id))
                                    <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary mt-2" {{ $subject->teachers->contains('pivot.section_id', $section->id) ? 'disabled' : '' }}>
                                    {{ $subject->teachers->contains('pivot.section_id', $section->id) ? 'Assigned' : 'Assign' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center">No subjects available for this grade.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#subjects').DataTable();

            // Handle form submission with SweetAlert
            $('.assign-teacher-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to assign this teacher to the subject.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, assign it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-layouts.app>