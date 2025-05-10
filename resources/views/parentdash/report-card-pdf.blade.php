<!DOCTYPE html>
<html>

<head>
    <title>Report Card</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            font-size: 13px;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #3674B5;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .header img {
            width: 80px;
        }

        .header h1 {
            margin: 5px 0 0 0;
            color: #3674B5;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 4px 0;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .bg-secondary {
            background-color: #6c757d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #aaa;
        }

        th {
            background-color: #3674B5;
            color: white;
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 8px;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: right;
        }

        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            width: 40%;
            border-top: 1px solid #aaa;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('assets/img/visionvoicelogo.png') }}" alt="School Logo">

        <h1>VisionVoice Academy</h1>
        <small>Official Student Report Card</small>
    </div>

    <div class="info">
        <p><strong>Student Name:</strong> {{ $student->application->first_name }} {{ $student->application->last_name }}</p>
        <p><strong>Grade:</strong> {{ $section->grade->name }}</p>
        <p><strong>Academic Year:</strong> {{ $section->academicYear->name }}</p>
        <p><strong>Status:</strong>
            @if($sectionStudent->status === 'pass')
            <span class="badge bg-success"> Passed</span>
            @elseif($sectionStudent->status === 'fail')
            <span class="badge bg-danger"> Failed</span>
            @else
            <span class="badge bg-secondary"> Active</span>
            @endif
        </p>
        <p><strong>Final Grade:</strong> {{ $sectionStudent->final_grade ?? 'N/A' }}</p>
    </div>

    <table>
        <thead>
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
                <td colspan="3">No exams found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature">
        <div>Teacher Signature</div>
        <div>Parent Signature</div>
    </div>

    <div class="footer">
        Generated on {{ now()->format('F j, Y') }}
    </div>

</body>

</html>