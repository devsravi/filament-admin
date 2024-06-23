<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;
use App\Models\City;
use App\Models\Employee;
use App\Models\State;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;
use Filament\Forms\Get;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    // protected static ?string $navigationGroup = 'Employee Management';

    public static function form(Form $form): Form
    {
        // dd(Country::query()->pluck('name', 'id'));
        return $form
            ->schema([
                Fieldset::make('Employee',)
                ->schema([
                    Select::make('country_id')
                    ->options(Country::query()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->live(),

                    Select::make('state_id')
                    ->options(fn (Get $get): Collection => State::query()
                        ->where('country_id', $get('country_id'))
                        ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                    Select::make('city_id')
                    ->options(fn (Get $get): Collection => City::query()
                        ->where('state_id', $get('state_id'))
                        ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                    Select::make('department_id')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->required(),

                    TextInput::make('first_name')
                    ->required()
                    ->minValue(2)
                    ->maxValue(100),

                    TextInput::make('last_name')
                    ->required()
                    ->minValue(2)
                    ->maxValue(100),

                    Textarea::make('address')
                    ->required()
                    ->minLength(2)
                    ->maxLength(1024),

                    TextInput::make('zip_code')
                    ->required()
                    ->minValue(2)
                    ->maxValue(100),

                    DatePicker::make('birth_date')
                    ->required()
                    ->native(false)
                    ->maxDate(now())
                    ->displayFormat('d/m/Y'),

                    DatePicker::make('date_hire')
                    ->required()
                    ->native(false)
                    ->maxDate(now())
                    ->displayFormat('d/m/Y'),

                    TextInput::make('phone')
                    ->required()
                    ->minValue(2)
                    ->maxValue(100),

                    TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord:true)
                    ->regex('/^.+@.+$/i')
                    ->maxValue(100),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable()->searchable(),
                TextColumn::make('date_hire')->date(),
                TextColumn::make('created_at')->date(),
            ])
            ->filters([
                SelectFilter::make('department')->relationship('department', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets(): array
    {
        return [
            EmployeeStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
