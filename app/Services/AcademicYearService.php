<?php
namespace App\Services;

use App\Models\AcademicYear;
use App\Repositories\AcademicYearRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AcademicYearService
{
	/**
     * @var AcademicYearRepository $academicYearRepository
     */
    protected $academicYearRepository;

    /**
     * DummyClass constructor.
     *
     * @param AcademicYearRepository $academicYearRepository
     */
    public function __construct(AcademicYearRepository $academicYearRepository)
    {
        $this->academicYearRepository = $academicYearRepository;
    }

    /**
     * Get all academicYearRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->academicYearRepository->all();
    }

    /**
     * Get academicYearRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->academicYearRepository->getById($id);
    }

    /**
     * Validate academicYearRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->academicYearRepository->save($data);
    }

    /**
     * Update academicYearRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $academicYearRepository = $this->academicYearRepository->update($data, $id);
            DB::commit();
            return $academicYearRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete academicYearRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $academicYearRepository = $this->academicYearRepository->delete($id);
            DB::commit();
            return $academicYearRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}
