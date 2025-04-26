<?php
namespace App\Repositories;

use App\Models\AcademicYear;

class AcademicYearRepository
{
	 /**
     * @var AcademicYear
     */
    protected AcademicYear $academicYear;

    /**
     * AcademicYear constructor.
     *
     * @param AcademicYear $academicYear
     */
    public function __construct(AcademicYear $academicYear)
    {
        $this->academicYear = $academicYear;
    }

    /**
     * Get all academicYear.
     *
     * @return AcademicYear $academicYear
     */
    public function all()
    {
        return $this->academicYear->get();
    }

     /**
     * Get academicYear by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->academicYear->find($id);
    }

    /**
     * Save AcademicYear
     *
     * @param $data
     * @return AcademicYear
     */
     public function save(array $data)
    {
        return AcademicYear::create($data);
    }

     /**
     * Update AcademicYear
     *
     * @param $data
     * @return AcademicYear
     */
    public function update(array $data, int $id)
    {
        $academicYear = $this->academicYear->find($id);
        $academicYear->update($data);
        return $academicYear;
    }

    /**
     * Delete AcademicYear
     *
     * @param $data
     * @return AcademicYear
     */
   	 public function delete(int $id)
    {
        $academicYear = $this->academicYear->find($id);
        $academicYear->delete();
        return $academicYear;
    }
}
