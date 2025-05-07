<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Students&nbsp;List&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Students&nbsp;List&nbsp;</span>
            </button>
        </p>

        <div class="table-responsive">
            <table id="applications" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Academic Year</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Profile Image</th>
                        <th>Date of Birth</th>
                        <th>Parents Name</th>
                        <th>Parents Phone Number</th>
                        <th>ID Card Image</th>
                        <th>Pre-Certificate</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Grade</th>
                        <th>Disabilities</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $student->application?->academicYear?->name ?? 'N/A' }}</td>
                        <td>{{ $student->application?->first_name ?? 'N/A' }}</td>
                        <td>{{ $student->application?->last_name ?? 'N/A' }}</td>
                        <td>{{ $student->user?->email ?? 'N/A' }}</td>
                        <td>
                            @if($student->application?->profile_img)
                            <a href="{{ Storage::url($student->application->profile_img) }}" target="_blank">
                                <img src="{{ Storage::url($student->application->profile_img) }}" alt="Profile Image" width="50">
                            </a>
                            @else
                            N/A
                            @endif
                        </td>

                        <td>{{ $student->application?->dob ?? 'N/A' }}</td>
                        <td>{{ $student->application?->parents_names ?? 'N/A' }}</td>
                        <td>{{ $student->application?->parents_contact_numbers ?? 'N/A' }}</td>
                        <td>
                            @if($student->application?->id_card_img)
                            <a href="{{ Storage::url($student->application->id_card_img) }}" target="_blank">
                                <img src="{{ Storage::url($student->application->id_card_img) }}" alt="ID Card" width="50">
                            </a>
                            @else
                            N/A
                            @endif
                        </td>

                        <td>
                            @if($student->application?->precertificate)
                            <button class="download-button" onclick="handleDocument('{{ Storage::url($student->application->precertificate) }}')">
                                <div class="docs">Docs</div>
                                <div class="download">
                                    <svg class="css-i6dzq1" fill="none" stroke="currentColor" stroke-width="2" height="24" width="24" viewBox="0 0 24 24">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line y2="3" x2="12" y1="15" x1="12"></line>
                                    </svg>
                                </div>
                            </button>
                            @else
                            N/A
                            @endif
                        </td>
                        <td>{{ $student->application?->gender ?? 'N/A' }}</td>
                        <td>{{ $student->application?->address ?? 'N/A' }}</td>
                        <td>{{ $student->application?->grade?->name ?? 'N/A' }}</td>
                        <td>
                            @if($student->application && $student->application->disabilities->isNotEmpty())
                            @foreach($student->application->disabilities as $disability)
                            {{ $disability->name }}@if(!$loop->last), @endif
                            @endforeach
                            @else
                            N/A
                            @endif
                        </td>
                        <td>{{ ucfirst($student->status ?? 'N/A') }}</td>
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
            $('#applications').DataTable({
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>

    <script>
        function handleDocument(url) {
            const fileExtension = url.split('.').pop().toLowerCase();

            if (fileExtension === 'pdf' || fileExtension === 'docx' || fileExtension === 'doc') {
                // فتح الملف بصفحة جديدة
                window.open(url, '_blank');

                // تحميل الملف تلقائيًا
                const link = document.createElement('a');
                link.href = url;
                link.download = url.split('/').pop(); // Automatically set file name
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                alert('Unsupported file type.');
            }
        }
    </script>


</x-layouts.app>
<style>
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