@if ($isReadOnly)
<div class="alert alert-warning">
    Attendance for this date is locked. You may only view records.
</div>
@endif
@foreach ($students as $student)
<div class="d-flex justify-content-between align-items-center border-bottom py-2">
    <span>{{ $student->application->first_name }}{{ $student->application->last_name }}</span>
    <div class="form-check form-switch">
        <input type="checkbox" class="form-check-input"
            name="attendances[{{ $student->id }}]"
            value="present"
            {{ ($attendanceMap[$student->id] ?? 'present') === 'present' ? 'checked' : '' }}
            {{ $isReadOnly ? 'disabled' : '' }}>


    </div>
</div>
@endforeach