<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\AcademicYear;
use App\Models\SectionStudent;
use App\Models\ExamStudentGrade;
class FinalizeAcademicYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
   
    protected $signature = 'finalize:academic-year';
    protected $description = 'Finalize expired academic years and evaluate students.';

    /**
     * The console command description.
     *
     * @var string
     */
      /**
     * Execute the console command.
     */
   

    public function handle()
    {
        $today = Carbon::now()->toDateString();

        $yearsToClose = AcademicYear::where('end_date', '<=', $today)
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($yearsToClose as $year) {
            $year->update(['status' => 'completed']);

            // Get all active section_student records in this academic year
            $sectionStudents = SectionStudent::where('academic_year_id', $year->id)
                ->where('status', 'active')
                ->get();

            foreach ($sectionStudents as $record) {
                $averageGrade = ExamStudentGrade::join('exams', 'exams.id', '=', 'exam_student_grades.exam_id')
                    ->join('section_subject_teacher', 'exams.section_subject_teacher_id', '=', 'section_subject_teacher.id')
                    ->where('exam_student_grades.student_id', $record->student_id)
                    ->where('section_subject_teacher.section_id', $record->section_id)
                    ->avg('exam_student_grades.grade');

                $newStatus = $averageGrade >= 50 ? 'pass' : 'fail';

                $record->update([
                    'status' => $newStatus,
                    'final_grade' => round($averageGrade, 2)
                ]);
            }

            $this->info("Academic Year {$year->name} finalized.");
        }
    }
}
