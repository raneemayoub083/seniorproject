<x-layouts.app>
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:#3674B5; text-shadow:1px 1px 2px rgba(0,0,0,0.1);">Welcome to Your Dashboard</h2>
            <p class="text-muted">Here's an overview of your child's academic information</p>
        </div>

        <div class="row">
            @foreach ($students as $student)
            <div class="col-md-6 mb-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary fw-bold">{{ $student->application->first_name }} {{ $student->application->last_name }}</h5>

                                <img src="{{ Storage::url($student->application->profile_img) }}" alt="Profile Image" width="50">

                            </div>
                        </div>


                        <hr>
                        @php
                        $activeSection = $student->activeSection();
                        @endphp

                        <p><strong>Grade:</strong> {{ $activeSection?->grade->name ?? 'N/A' }}</p>
                        <p><strong>Academic Year:</strong> {{ $activeSection?->academicYear?->name ?? 'N/A' }}</p>
                        <a href="/parent/classes" class="btn btn-sm" style="background-color:#3674B5; color:white;">View Grades</a>
                        <a href="/parent/classes" class="btn btn-sm btn-outline-secondary">View Attendance</a>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="text-muted">Attendance</h6>
                                <canvas id="attendanceChart{{ $student->id }}"></canvas>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Average Grades</h6>
                                <canvas id="gradesChart{{ $student->id }}"></canvas>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($students->isEmpty())
        <div class="alert alert-info text-center">You have no students linked to your account.</div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const studentCharts = JSON.parse('{!! json_encode($studentCharts) !!}');
        document.addEventListener("DOMContentLoaded", function() {
            Object.entries(studentCharts).forEach(([studentId, data]) => {
                // Attendance Chart
                new Chart(document.getElementById(`attendanceChart${studentId}`), {
                    type: 'doughnut',
                    data: {
                        labels: ['Present', 'Absent'],
                        datasets: [{
                            data: [data.attendance.present, data.attendance.absent],
                            backgroundColor: ['#28a745', '#dc3545']
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Grades Chart
                new Chart(document.getElementById(`gradesChart${studentId}`), {
                    type: 'bar',
                    data: {
                        labels: Object.keys(data.grades),
                        datasets: [{
                            label: 'Average Grade',
                            data: Object.values(data.grades),
                            backgroundColor: '#3674B5'
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
        });
    </script>


    @endpush
</x-layouts.app>