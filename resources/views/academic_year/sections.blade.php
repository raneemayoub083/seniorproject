<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Sections&nbsp;for&nbsp;Academic&nbsp;Year:&nbsp;{{ $academicYear->name }}&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Sections&nbsp;for&nbsp;Academic&nbsp;Year:&nbsp;{{ $academicYear->name }}&nbsp;</span>
            </button>
        </p>

        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('academic_year.index') }}" class="btn btn-success">Back to Academic Years</a>
        </div>

        <div class="table-responsive">
            <table id="academicyears" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Grade</th>
                        <th>Capacity</th>
                        <th>View Students</th>
                        <th>View Subjects</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($academicYear->sections as $section)
                    <tr>
                        <td>{{ $section->grade->name }}</td>
                        <td>{{ $section->capacity }}</td>
                        <td>
                            <a href="{{ route('academic_year.students', ['id' => $section->id]) }}" class="btn btn-info">View Students</a>
                        </td>
                        <td>
                            <a href="{{ route('academic_year.subjects', ['id' => $section->id]) }}" class="btn btn-info">View Subjects</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No sections available for this academic year.</td>
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
            $('#academicyears').DataTable();

            // Delete confirmation with SweetAlert2
            $('.delete-btn').on('click', function() {
                var sectionId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will not be able to recover this section!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/sections/delete/' + sectionId;
                    }
                });
            });
        });
    </script>
</x-layouts.app>