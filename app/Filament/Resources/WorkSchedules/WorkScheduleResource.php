<?php

namespace App\Filament\Resources\WorkSchedules;

use App\Filament\Resources\WorkSchedules\Pages\CreateWorkSchedule;
use App\Filament\Resources\WorkSchedules\Pages\EditWorkSchedule;
use App\Filament\Resources\WorkSchedules\Pages\ListWorkSchedules;
use App\Filament\Resources\WorkSchedules\Schemas\WorkScheduleForm;
use App\Filament\Resources\WorkSchedules\Tables\WorkSchedulesTable;
use App\Models\WorkSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WorkScheduleResource extends Resource
{
    protected static ?string $model = WorkSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string | UnitEnum | null $navigationGroup = 'Pengaturan';
    
    protected static ?string $recordTitleAttribute = 'Jadwal kerja';

    protected static ?string $modelLabel = 'jadwal kerja';

    public static function form(Schema $schema): Schema
    {
        return WorkScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkSchedulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkSchedules::route('/'),
            'create' => CreateWorkSchedule::route('/create'),
            'edit' => EditWorkSchedule::route('/{record}/edit'),
        ];
    }
}
