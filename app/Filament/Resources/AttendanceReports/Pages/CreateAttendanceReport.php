<?php

namespace App\Filament\Resources\AttendanceReports\Pages;

use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendanceReport extends CreateRecord
{
    protected static string $resource = AttendanceReportResource::class;
}
