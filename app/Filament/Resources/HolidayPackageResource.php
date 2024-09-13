<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HolidayPackageResource\Pages;
use App\Filament\Resources\HolidayPackageResource\RelationManagers;
use App\Models\Category;
use App\Models\HolidayPackage;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HolidayPackageResource extends Resource
{
    protected static ?string $model = HolidayPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')
                    ->required(),
                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->native(false),
                TextInput::make('price')
                    ->prefix('IDR')
                    ->numeric()
                    ->required(),
                TextInput::make('duration')
                    ->prefix('Days')
                    ->numeric()
                    ->required(),
                TextInput::make('location')
                    ->required(),
                Textarea::make('description')
                    ->rows(3)
                    ->required(),
                Select::make('available')
                    ->options([
                        false => 'Not Available',
                        true => 'Available',
                    ])
                    ->required()
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Category.name'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration (Days)'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\IconColumn::make('available')
                    ->boolean()
                    ->label('Available')
                    ->trueColor('success')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseColor('danger')
                    ->falseIcon('heroicon-o-x-circle'),

            ])
            ->filters([
                //
            ])
            ->actions([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHolidayPackages::route('/'),
            'create' => Pages\CreateHolidayPackage::route('/create'),
            'edit' => Pages\EditHolidayPackage::route('/{record}/edit'),
        ];
    }
}
