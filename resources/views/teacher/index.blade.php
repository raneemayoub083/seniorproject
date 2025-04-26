<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Teachers&nbsp;List&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Teachers&nbsp;List&nbsp;</span>
            </button>
        </p>
        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('teacher.create') }}" class="btn btn-success">Create New Teacher</a>
        </div>
        <div class="table-responsive">
            <table id="teachers" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Academic Year</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Profile Image</th>
                        <th>CV</th>
                        <th>Subjects</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teachers as $teacher)
                    <tr>
                        <td>{{ $teacher->academicYear->name }}</td>
                        <td>{{ $teacher->first_name }}</td>
                        <td>{{ $teacher->last_name }}</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" id="status-{{ $teacher->id }}" {{ $teacher->status ? 'checked' : '' }} onchange="confirmStatusUpdate({{ $teacher->id }}, this.checked)">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->phone }}</td>
                        <td>{{ $teacher->address }}</td>
                        <td>
                            @if($teacher->profile_img)
                            <img src="{{ Storage::url($teacher->profile_img) }}" alt="Profile Image" width="50">
                            @else
                            N/A
                            @endif
                        </td>
                        <td><a href="#" onclick="handleDocument('{{ Storage::url($teacher->cv) }}'); return false;">
                                <button class="download-button">
                                    <div class="docs">
                                        <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke-width="2" stroke="currentColor" height="20" width="20" viewBox="0 0 24 24">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line y2="13" x2="8" y1="13" x1="16"></line>
                                            <line y2="17" x2="8" y1="17" x1="16"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg> Docs
                                    </div>
                                    <div class="download">
                                        <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke-width="2" stroke="currentColor" height="24" width="24" viewBox="0 0 24 24">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line y2="3" x2="12" y1="15" x1="12"></line>
                                        </svg>
                                    </div>
                                </button>
                            </a></td>
                        <td>
                            @foreach($teacher->subjects as $subject)
                            {{ $subject->name }}@if(!$loop->last), @endif
                            @endforeach
                        </td>
                        <td>
                            <button class="btn btn-danger" onclick="confirmDelete('{{ $teacher->id }}')">Delete</button>
                            <form id="delete-form-{{ $teacher->id }}" action="{{ route('teacher.destroy', ['id' => $teacher->id]) }}" method="POST" style="display: none;">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>

    <script>
        function handleDocument(url) {
            const fileExtension = url.split('.').pop().toLowerCase();

            if (fileExtension === 'pdf' || fileExtension === 'docx' || fileExtension === 'doc') {
                window.open(url, '_blank');
                const link = document.createElement('a');
                link.href = url;
                link.download = url.split('/').pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                alert('Unsupported file type.');
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#teachers').DataTable({
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

        // SweetAlert Confirmation for Status Update
        function confirmStatusUpdate(teacherId, isActive) {
            Swal.fire({
                title: 'Are you sure?',
                text: isActive ? "You are activating this teacher's account!" : "You are deactivating this teacher's account!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update status!'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStatus(teacherId, isActive); // Proceed with status update
                } else {
                    // Reset the checkbox if the user cancels the action
                    document.getElementById('status-' + teacherId).checked = !isActive;
                }
            });
        }

        function updateStatus(teacherId, isActive) {
            $.ajax({
                url: '/teachers/update-status/' + teacherId,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: isActive ? 1 : 0,
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Teacher status updated successfully.',
                            confirmButtonColor: '#3674B5',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update teacher status.',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating the status.',
                    });
                }
            });
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
    /* From Uiverse.io by arghyaBiswasDev */
    /* The switch - the box around the slider */
    .switch {
        font-size: 17px;
        position: relative;
        display: inline-block;
        width: 3.5em;
        height: 2em;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #fff;
        border: 1px solid #adb5bd;
        transition: .4s;
        border-radius: 30px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 1.4em;
        width: 1.4em;
        border-radius: 20px;
        left: 0.27em;
        bottom: 0.25em;
        background-color: #adb5bd;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #007bff;
        border: 1px solid #007bff;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #007bff;
    }

    input:checked+.slider:before {
        transform: translateX(1.4em);
        background-color: #fff;
    }

    .download-button {
        position: relative;
        border-width: 0;
        color: white;
        font-size: 15px;
        font-weight: 600;
        border-radius: 4px;
        z-index: 1;
    }

    .download-button .docs {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        min-height: 40px;
        padding: 0 10px;
        border-radius: 4px;
        z-index: 1;
        background-color: #3674B5;
        border: solid 1px #e8e8e82d;
        transition: all .5s cubic-bezier(0.77, 0, 0.175, 1);
    }

    .download-button:hover {
        box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
    }

    .download {
        background: #3674B5;
        box-shadow: 0 0 25px #3674B5;
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 90%;
        margin: 0 auto;
        z-index: -1;
        border-radius: 4px;
        transform: translateY(0%);
        background-color: #3674B5;
        border: solid 1px #01e0572d;
        transition: all .5s cubic-bezier(0.77, 0, 0.175, 1);
    }

    .download-button:hover .download {
        transform: translateY(100%)
    }

    .download svg polyline,
    .download svg line {
        animation: docs 1s infinite;
    }

    @keyframes docs {
        0% {
            transform: translateY(0%);
        }

        50% {
            transform: translateY(-15%);
        }

        100% {
            transform: translateY(0%);
        }
    }

    /* From Uiverse.io by mrhyddenn */
    .shadow__btn {
        padding: 10px 20px;
        border: none;
        font-size: 17px;
        color: white;
        border-radius: 7px;
        letter-spacing: 4px;
        font-weight: 700;
        text-transform: uppercase;
        transition: 0.5s;
        transition-property: box-shadow;
    }

    .shadow__btn a {
        color: white;
        text-decoration: none;
    }

    .shadow__btn {
        background: #3674B5;
        box-shadow: 0 0 25px #3674B5;
    }

    .shadow__btn:hover {
        box-shadow: 0 0 5px #3674B5,
            0 0 25px #3674B5,
            0 0 50px #3674B5,
            0 0 100px #3674B5;
    }
</style>