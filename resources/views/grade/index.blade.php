<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Grades&nbsp;List&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Grades&nbsp;List&nbsp;</span>
            </button>
        </p>
        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('grade.create') }}" class="btn btn-success">Create New Grade</a>
        </div>
        <div class="table-responsive">
            <table id="grade" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Grade Name</th>
                        <th>Subjects</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grade)
                    <tr>
                        <td>{{ $grade->name }}</td>
                        <td>
                            <div class="subjects-list">
                                @foreach($grade->subjects as $subject)
                                <div class="subject-item">
                                    <i class="fas fa-book"></i>
                                    <span style="color:white">{{ $subject->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td>
   
                            <a href="{{ route('grade.edit', ['id' => $grade->id]) }}" class="btn btn-warning">Edit</a>
                            <button class="btn btn-danger" onclick="confirmDelete('{{ $grade->id }}')">Delete</button>

                            <form id="delete-form-{{ $grade->id }}" action="{{ route('grade.destroy', ['id' => $grade->id]) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>

                        </td>
                    </tr>
                    @endforeach
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
            $('#grade').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records",
                }
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3674B5',
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            confirmButtonColor: '#3674B5',
        });
    </script>
    @endif
</x-layouts.app>

<style>
    .subjects-list {
        list-style-type: none;
        /* Removes default list bullets */
        padding: 0;
    }

    .subject-item {
        padding: 5px;
        margin: 5px 0;
        background-color: #3674B5;
        border-radius: 4px;
        display: flex;
        align-items: center;
    }

    .subject-item i {
        margin-right: 10px;
        /* Adds space between icon and subject name */
        color: white;
        /* Optional: color for the icon */
    }

    .subject-item span {
        font-weight: 500;
    }

    .svgIcon {
        width: 16px;
        height: 16px;
        fill: currentColor;
    }
</style>