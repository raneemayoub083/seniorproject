<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Subject;

class GradeSubjectSeeder extends Seeder
{
    public function run()
    {
        $grades = [
            'Grade 1',
            'Grade 2',
            'Grade 3',
            'Grade 4',
            'Grade 5',
            'Grade 6',
            'Grade 7',
            'Grade 8',
            'Grade 9',
            'Grade 10',
            'Grade 11',
            'Grade 12'
        ];

        $subjects = [
            'English',
            'Arabic',
            'Math',
            'Science',
            'Physics',
            'Chemistry',
            'Biology',
            'Geography',
            'Civics'
        ];

        foreach ($grades as $gradeName) {
            $grade = Grade::firstOrCreate(['name' => $gradeName]);

            if (in_array($gradeName, ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5'])) {
                $subjectNames = ['English', 'Arabic', 'Math', 'Science'];
            } else {
                $subjectNames = ['English', 'Math', 'Physics', 'Chemistry', 'Biology', 'Arabic', 'Geography', 'Civics'];
            }

            foreach ($subjectNames as $subjectName) {
                $subject = Subject::firstOrCreate(['name' => $subjectName]);
                $grade->subjects()->syncWithoutDetaching($subject->id);
            }
        }
    }
}
