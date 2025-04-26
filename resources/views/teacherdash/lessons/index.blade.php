<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Lessons&nbsp;for&nbsp;Academic&nbsp;Year&nbsp;{{$academicYearName }}&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Lessons&nbsp;for&nbsp;Academic&nbsp;Year&nbsp;{{$academicYearName }}&nbsp;</span>
            </button>
        </p>
        <div class="table-responsive">
            <table id="lessonsTable" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Video</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lessons as $lesson)
                    <tr>
                        <td>{{ $lesson->title }}</td>
                        <td>{{ $lesson->description }}</td>
                        <td>
                            @if($lesson->video)
                            <a href="{{ Storage::url($lesson->video) }}" target="_blank">View Video</a>
                            @else
                            N/A
                            @endif
                        </td>
                        <td>
                            @if($lesson->pdf)
                            <a href="{{ Storage::url($lesson->pdf) }}" target="_blank">View PDF</a>
                            @else
                            N/A
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
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#lessonsTable').DataTable();
        });
    </script>
</x-layouts.app>