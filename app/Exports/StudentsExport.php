<?php

namespace App\Exports;

use App\Models\Student;  // Adjust the model as per your application
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    protected $sectionId;

    public function __construct($sectionId)
    {
        $this->sectionId = $sectionId;
    }

    public function collection()
    {
        // Fetch students for the specific section using the pivot table
        return Student::join('section_student', 'students.id', '=', 'section_student.student_id')
            ->join('applications', 'students.application_id', '=', 'applications.id')
            ->where('section_student.section_id', $this->sectionId)
            ->select('applications.first_name', 'applications.last_name')
            ->get();
    }

    public function headings(): array
    {
        return ['First Name', 'Last Name'];
    }
}