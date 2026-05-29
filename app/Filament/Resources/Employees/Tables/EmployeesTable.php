<?php

namespace App\Filament\Resources\Employees\Tables;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\Employee;
use App\Models\SubCity;
use App\Models\Woreda;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->getStateUsing(fn ($record): ?string => $record->photo_url)
                    ->circular(),

                \Filament\Tables\Columns\TextColumn::make('employee_id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('full_name_am')
                    ->label('Name (አማርኛ)')
                    ->getStateUsing(fn($record) => "{$record->first_name_am} {$record->last_name_am}")
                    ->searchable(['first_name_am', 'last_name_am']),

                \Filament\Tables\Columns\TextColumn::make('full_name_en')
                    ->label('Name (English)')
                    ->getStateUsing(fn($record) => "{$record->first_name_en} {$record->last_name_en}")
                    ->searchable(['first_name_en', 'last_name_en'])
                    ->toggleable(isToggledHiddenByDefault: true),

                \Filament\Tables\Columns\TextColumn::make('position')
                    ->label('Position')
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('location_type')
                    ->label('Office')
                    ->formatStateUsing(fn (?string $state): string => $state === 'head_office' ? 'Head Office' : 'Sub City / Woreda')
                    ->badge()
                    ->toggleable(),

                \Filament\Tables\Columns\TextColumn::make('job_level')
                    ->label('Level')
                    ->sortable()
                    ->toggleable(),

                \Filament\Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'on_leave' => 'warning',
                        'terminated' => 'danger',
                        default => 'secondary',
                    }),

                \Filament\Tables\Columns\TextColumn::make('hire_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'on_leave' => 'On Leave',
                        'terminated' => 'Terminated',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('location_type')
                    ->label('Office Type')
                    ->options([
                        'sub_city' => 'Sub City / Woreda Office',
                        'head_office' => 'Head Office',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('position')
                    ->label('Job Position')
                    ->options(fn () => Employee::jobPositionOptions())
                    ->searchable(),
                \Filament\Tables\Filters\SelectFilter::make('job_level')
                    ->label('Level')
                    ->options(fn () => Employee::jobLevelOptions()),
                \Filament\Tables\Filters\TrashedFilter::make(),
<<<<<<< HEAD:hr-callcenter-system/app/Filament/Resources/Employees/Tables/EmployeesTable.php

                \Filament\Tables\Filters\SelectFilter::make('sub_city_id')
                    ->label('Sub City')
                    ->options(function (): array {
                        $query = SubCity::query()->orderBy('code');

                        if (EmployeeResource::shouldLimitToAssignedSubCity()) {
                            $query->whereKey(EmployeeResource::assignedSubCityId());
                        }

                        return $query->pluck('name_am', 'id')->all();
                    }),

                \Filament\Tables\Filters\SelectFilter::make('woreda_id')
                    ->label('Woreda')
                    ->options(function (): array {
                        $query = Woreda::query()->orderBy('code');

                        if (EmployeeResource::shouldLimitToAssignedSubCity()) {
                            $query->where('sub_city_id', EmployeeResource::assignedSubCityId());
                        }

                        return $query->pluck('name_am', 'id')->all();
                    }),

                \Filament\Tables\Filters\SelectFilter::make('shirt_size')
                    ->label('Shirt Size')
                    ->options(fn() => \App\Models\Employee::whereNotNull('shirt_size')->distinct()->pluck('shirt_size', 'shirt_size')),

                \Filament\Tables\Filters\SelectFilter::make('pant_size')
                    ->label('Pant Size')
                    ->options(fn() => \App\Models\Employee::whereNotNull('pant_size')->distinct()->pluck('pant_size', 'pant_size')),
=======
>>>>>>> eda5f637f61aba7a99db1ae1b51ac1ad4e697aba:app/Filament/Resources/Employees/Tables/EmployeesTable.php
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
