<?php

namespace App\Filament\Resources\Payrolls\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'salaryComponents';

    protected static ?string $title = 'Komponen Gaji';

    protected static ?string $modelLabel = 'Komponen';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Tipe')
                    ->options(['bonus' => 'Bonus', 'deduction' => 'Potongan'])
                    ->required()
                    ->native(false),
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('amount')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->minValue(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === 'bonus' ? 'Bonus' : 'Potongan')
                    ->color(fn(string $state): string => match ($state) {
                        'bonus' => 'success',
                        'deduction' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('name')
                    ->label('Nama Komponen')
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'bonus' => 'Bonus',
                        'deduction' => 'Potongan',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading('Tambah Komponen Gaji'),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Edit Komponen Gaji'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
