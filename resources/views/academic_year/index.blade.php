<x-layouts.app>
    <div class="container">
        <p class="mt-4" style="color:#729762;text-align:center;text-shadow:2px 2px 5px white;">
            <button data-text="Awesome" class="buttonpma">
                <span class="actual-text">&nbsp;Academic&nbsp;Years&nbsp;List&nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp;Academic&nbsp;Years&nbsp;List&nbsp;</span>
            </button>
        </p>
        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('academic_year.create') }}" class="btn btn-success">Create New Academic Year</a>
        </div>
        <div class="table-responsive">
            <table id="academicyears" class="table table-striped table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Academic Year Name</th>
                        <th>Sections</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Application Opening Date</th>
                        <th>Application Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($academicYears as $academicYear)
                    <tr>
                        <td>{{ $academicYear->name }}</td>
                        <td>
                            <!-- Link to view sections of the current academic year -->
                            <a href="{{ route('academic_year.sections', ['id' => $academicYear->id]) }}" class="btn btn-info">View Sections</a>
                        </td>
                        <td>{{ $academicYear->start_date }}</td>
                        <td>{{ $academicYear->end_date }}</td>
                        <td>{{ $academicYear->application_opening }}</td>
                        <td>{{ $academicYear->application_expiry }}</td>
                        <td>
                            @if($academicYear->status == 'pending')
                            <div class="wrapper">
                                <div class="c"></div>
                                <div class="c"></div>
                                <div class="c"></div>
                                <div class="s"></div>
                                <div class="s"></div>
                                <div class="s"></div>
                            </div>
                            @elseif($academicYear->status == 'opened')
                            <div class="hourglassBackground">
                                <div class="hourglassContainer">
                                    <div class="hourglassCurves"></div>
                                    <div class="hourglassCapTop"></div>
                                    <div class="hourglassGlassTop"></div>
                                    <div class="hourglassSand"></div>
                                    <div class="hourglassSandStream"></div>
                                    <div class="hourglassCapBottom"></div>
                                    <div class="hourglassGlass"></div>
                                </div>
                            </div>
                            @elseif($academicYear->status == 'completed')
                            <div class="checkbox-wrapper-31">
                                <input checked="" type="checkbox">
                                <svg viewBox="0 0 35.6 35.6">
                                    <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                                    <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                                    <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                                </svg>
                            </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('academic_year.edit', ['id' => $academicYear->id]) }}" class="btn btn-warning">Edit</a>

                            <button class="btn btn-danger" onclick="confirmDelete('{{ $academicYear->id }}')">Delete</button>

                            <form id="delete-form-{{ $academicYear->id }}" action="{{ route('academic_year.destroy', ['id' => $academicYear->id]) }}" method="POST" style="display: none;">
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
            $('#academicyears').DataTable({
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
    </script>

</x-layouts.app>
<style>
    /* From Uiverse.io by guilhermeyohan */
    .checkbox-wrapper-31:hover .check {
        stroke-dashoffset: 0;
    }

    .checkbox-wrapper-31 {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 40px;
    }

    .checkbox-wrapper-31 .background {
        fill: #3674B5;
        transition: ease all 0.6s;
        -webkit-transition: ease all 0.6s;
    }

    .checkbox-wrapper-31 .stroke {
        fill: none;
        stroke: #3674B5;
        stroke-miterlimit: 10;
        stroke-width: 2px;
        stroke-dashoffset: 100;
        stroke-dasharray: 100;
        transition: ease all 0.6s;
        -webkit-transition: ease all 0.6s;
    }

    .checkbox-wrapper-31 .check {
        fill: none;
        stroke: #fff;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-width: 2px;
        stroke-dashoffset: 22;
        stroke-dasharray: 22;
        transition: ease all 0.6s;
        -webkit-transition: ease all 0.6s;
    }

    .checkbox-wrapper-31 input[type=checkbox] {
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        margin: 0;
        opacity: 0;
        -appearance: none;
        appearance: none;
        -webkit-appearance: none;
    }

    .checkbox-wrapper-31 input[type=checkbox]:hover {
        cursor: pointer;
    }

    .checkbox-wrapper-31 input[type=checkbox]:checked+svg .background {
        fill: #3674B5;
    }

    .checkbox-wrapper-31 input[type=checkbox]:checked+svg .stroke {
        stroke-dashoffset: 0;
    }

    .checkbox-wrapper-31 input[type=checkbox]:checked+svg .check {
        stroke-dashoffset: 0;
    }

    /* From Uiverse.io by SouravBandyopadhyay */
    .hourglassBackground {
        position: relative;
        background-color:
            #3674B5;
        height: 130px;
        width: 130px;
        border-radius: 50%;
        margin: 30px auto;
    }

    .hourglassContainer {
        position: absolute;
        top: 30px;
        left: 40px;
        width: 50px;
        height: 70px;
        -webkit-animation: hourglassRotate 2s ease-in 0s infinite;
        animation: hourglassRotate 2s ease-in 0s infinite;
        transform-style: preserve-3d;
        perspective: 1000px;
    }

    .hourglassContainer div,
    .hourglassContainer div:before,
    .hourglassContainer div:after {
        transform-style: preserve-3d;
    }

    @-webkit-keyframes hourglassRotate {
        0% {
            transform: rotateX(0deg);
        }

        50% {
            transform: rotateX(180deg);
        }

        100% {
            transform: rotateX(180deg);
        }
    }

    @keyframes hourglassRotate {
        0% {
            transform: rotateX(0deg);
        }

        50% {
            transform: rotateX(180deg);
        }

        100% {
            transform: rotateX(180deg);
        }
    }

    .hourglassCapTop {
        top: 0;
    }

    .hourglassCapTop:before {
        top: -25px;
    }

    .hourglassCapTop:after {
        top: -20px;
    }

    .hourglassCapBottom {
        bottom: 0;
    }

    .hourglassCapBottom:before {
        bottom: -25px;
    }

    .hourglassCapBottom:after {
        bottom: -20px;
    }

    .hourglassGlassTop {
        transform: rotateX(90deg);
        position: absolute;
        top: -16px;
        left: 3px;
        border-radius: 50%;
        width: 44px;
        height: 44px;
        background-color: white;
    }

    .hourglassGlass {
        perspective: 100px;
        position: absolute;
        top: 32px;
        left: 20px;
        width: 10px;
        height: 6px;
        background-color: white;
        opacity: 0.5;
    }

    .hourglassGlass:before,
    .hourglassGlass:after {
        content: '';
        display: block;
        position: absolute;
        background-color: #999999;
        left: -17px;
        width: 44px;
        height: 28px;
    }

    .hourglassGlass:before {
        top: -27px;
        border-radius: 0 0 25px 25px;
    }

    .hourglassGlass:after {
        bottom: -27px;
        border-radius: 25px 25px 0 0;
    }

    .hourglassCurves:before,
    .hourglassCurves:after {
        content: '';
        display: block;
        position: absolute;
        top: 32px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #333;
        animation: hideCurves 2s ease-in 0s infinite;
    }

    .hourglassCurves:before {
        left: 15px;
    }

    .hourglassCurves:after {
        left: 29px;
    }

    @-webkit-keyframes hideCurves {
        0% {
            opacity: 1;
        }

        25% {
            opacity: 0;
        }

        30% {
            opacity: 0;
        }

        40% {
            opacity: 1;
        }

        100% {
            opacity: 1;
        }
    }

    @keyframes hideCurves {
        0% {
            opacity: 1;
        }

        25% {
            opacity: 0;
        }

        30% {
            opacity: 0;
        }

        40% {
            opacity: 1;
        }

        100% {
            opacity: 1;
        }
    }

    .hourglassSandStream:before {
        content: '';
        display: block;
        position: absolute;
        left: 24px;
        width: 3px;
        background-color: white;
        -webkit-animation: sandStream1 2s ease-in 0s infinite;
        animation: sandStream1 2s ease-in 0s infinite;
    }

    .hourglassSandStream:after {
        content: '';
        display: block;
        position: absolute;
        top: 36px;
        left: 19px;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-bottom: 6px solid #fff;
        animation: sandStream2 2s ease-in 0s infinite;
    }

    @-webkit-keyframes sandStream1 {
        0% {
            height: 0;
            top: 35px;
        }

        50% {
            height: 0;
            top: 45px;
        }

        60% {
            height: 35px;
            top: 8px;
        }

        85% {
            height: 35px;
            top: 8px;
        }

        100% {
            height: 0;
            top: 8px;
        }
    }

    @keyframes sandStream1 {
        0% {
            height: 0;
            top: 35px;
        }

        50% {
            height: 0;
            top: 45px;
        }

        60% {
            height: 35px;
            top: 8px;
        }

        85% {
            height: 35px;
            top: 8px;
        }

        100% {
            height: 0;
            top: 8px;
        }
    }

    @-webkit-keyframes sandStream2 {
        0% {
            opacity: 0;
        }

        50% {
            opacity: 0;
        }

        51% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        91% {
            opacity: 0;
        }

        100% {
            opacity: 0;
        }
    }

    @keyframes sandStream2 {
        0% {
            opacity: 0;
        }

        50% {
            opacity: 0;
        }

        51% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        91% {
            opacity: 0;
        }

        100% {
            opacity: 0;
        }
    }

    .hourglassSand:before,
    .hourglassSand:after {
        content: '';
        display: block;
        position: absolute;
        left: 6px;
        background-color: white;
        perspective: 500px;
    }

    .hourglassSand:before {
        top: 8px;
        width: 39px;
        border-radius: 3px 3px 30px 30px;
        animation: sandFillup 2s ease-in 0s infinite;
    }

    .hourglassSand:after {
        border-radius: 30px 30px 3px 3px;
        animation: sandDeplete 2s ease-in 0s infinite;
    }

    @-webkit-keyframes sandFillup {
        0% {
            opacity: 0;
            height: 0;
        }

        60% {
            opacity: 1;
            height: 0;
        }

        100% {
            opacity: 1;
            height: 17px;
        }
    }

    @keyframes sandFillup {
        0% {
            opacity: 0;
            height: 0;
        }

        60% {
            opacity: 1;
            height: 0;
        }

        100% {
            opacity: 1;
            height: 17px;
        }
    }

    @-webkit-keyframes sandDeplete {
        0% {
            opacity: 0;
            top: 45px;
            height: 17px;
            width: 38px;
            left: 6px;
        }

        1% {
            opacity: 1;
            top: 45px;
            height: 17px;
            width: 38px;
            left: 6px;
        }

        24% {
            opacity: 1;
            top: 45px;
            height: 17px;
            width: 38px;
            left: 6px;
        }

        25% {
            opacity: 1;
            top: 41px;
            height: 17px;
            width: 38px;
            left: 6px;
        }

        50% {
            opacity: 1;
            top: 41px;
            height: 17px;
            width: 38px;
            left: 6px;
        }

        90% {
            opacity: 1;
            top: 41px;
            height: 0;
            width: 10px;
            left: 20px;
        }
    }

    @keyframes sandDeplete {
        0% {
            opacity: 0;
            top: 45px;
            height: 17px;
            width: 38px;
            left: 6px;
        }

        1% {
            opacity: 1;
            top: 45px;
            height: 17px;
            width: 38px;
            left: 6px;
        }

        24% {
            opacity: 1;
            top: 45px;
            height: 17px;
            width: 38px;
            left: 6px;
        }

        25% {
            opacity: 1;
            top: 41px;
            height: 17px;
            width: 38px;
            left: 6px;
        }

        50% {
            opacity: 1;
            top: 41px;
            height: 17px;
            width: 38px;
            left: 6px;
        }

        90% {
            opacity: 1;
            top: 41px;
            height: 0;
            width: 10px;
            left: 20px;
        }
    }

    /* From Uiverse.io by mobinkakei */
    .wrapper {
        width: 200px;
        height: 60px;
        position: relative;
        z-index: 1;
    }

    .c {
        width: 20px;
        height: 20px;
        position: absolute;
        border-radius: 50%;
        background-color: #3674B5;
        left: 15%;
        transform-origin: 50%;
        animation: circle7124 .5s alternate infinite ease;
    }

    @keyframes circle7124 {
        0% {
            top: 60px;
            height: 5px;
            border-radius: 50px 50px 25px 25px;
            transform: scaleX(1.7);
        }

        40% {
            height: 20px;
            border-radius: 50%;
            transform: scaleX(1);
        }

        100% {
            top: 0%;
        }
    }

    .c:nth-child(2) {
        left: 45%;
        animation-delay: .2s;
    }

    .c:nth-child(3) {
        left: auto;
        right: 15%;
        animation-delay: .3s;
    }

    .s {
        width: 20px;
        height: 4px;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.9);
        position: absolute;
        top: 62px;
        transform-origin: 50%;
        z-index: -1;
        left: 15%;
        filter: blur(1px);
        animation: shadow046 .5s alternate infinite ease;
    }

    @keyframes shadow046 {
        0% {
            transform: scaleX(1.5);
        }

        40% {
            transform: scaleX(1);
            opacity: .7;
        }

        100% {
            transform: scaleX(.2);
            opacity: .4;
        }
    }

    .s:nth-child(4) {
        left: 45%;
        animation-delay: .2s
    }

    .s:nth-child(5) {
        left: auto;
        right: 15%;
        animation-delay: .3s;
    }
</style>