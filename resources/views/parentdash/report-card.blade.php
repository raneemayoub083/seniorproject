<x-layouts.app>
    <div class="container py-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color:#3674B5;">📄 Report Card</h2>

            <p class="text-muted">Academic Overview for {{ $student->application->first_name }} {{ $student->application->last_name }}</p>
            
                <a href="{{ route('parentdash.reportCard.download', ['student' => $student->id, 'section' => $section->id]) }}"
                    class="btn btn-outline-primary">
                    📥 Download as PDF
                </a>
           

        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <h5><strong>Student:</strong> {{ $student->application->first_name }} {{ $student->application->last_name }}</h5>
                <h6><strong>Grade:</strong> {{ $section->grade->name }}</h6>
                <h6><strong>Academic Year:</strong> {{ $section->academicYear->name }}</h6>
                <h6><strong>Status:</strong>
                    @if($sectionStudent->status === 'pass')
                    <span class="badge bg-success">✅ Passed</span>
                    @elseif($sectionStudent->status === 'fail')
                    <span class="badge bg-danger">❌ Failed</span>
                    @else
                    <span class="badge bg-secondary">⏳ Active</span>
                    @endif
                </h6>
                <h6><strong>Final Grade:</strong> {{ $sectionStudent->final_grade ?? 'N/A' }}</h6>
            </div>
        </div>

        <div class="table-responsive shadow-sm border rounded">
            <table class="table table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Subject</th>
                        <th>Exam Title</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                    <tr>
                        <td>{{ $exam->exam->sectionSubjectTeacher->subject->name ?? 'N/A' }}</td>
                        <td>{{ $exam->exam->event->title ?? 'N/A' }}</td>
                        <td>{{ $exam->grade ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No exams found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-end mt-4">
            <small class="text-muted">Generated on {{ now()->format('F j, Y') }}</small>
        </div>
    </div>
</x-layouts.app>