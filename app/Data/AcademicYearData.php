<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Date;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Enum;
use App\Enums\AcademicYearEnum;


class AcademicYearData extends Data
{
    #[Max(255)]
    public string $name;
    #[Date]
    public Carbon $start_date;
    #[Date]
    public Carbon $end_date;
    #[Enum(AcademicYearEnum::class)]
    public AcademicYearEnum $status;

}
