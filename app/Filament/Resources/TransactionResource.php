<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\HolidayPackage;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            Select::make('user_id')
                ->label('User')
                ->options(User::all()->pluck('name', 'id'))
                ->required()
                ->native(false),
            TextInput::make('transaction_trx')
                ->label('Transaction ID')
                ->required()
                ->readOnly()
                ->default(fn () => Transaction::generateUniqueText()),
            TextInput::make('phone_number')
                ->label('Phone Number')
                ->required(),
            Select::make('holiday_package_id')
                ->label('Holiday Package')
                ->options(HolidayPackage::where('available', true)->get()->pluck('name', 'id'))
                ->required()
                ->native(false)
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $holidayPackage = HolidayPackage::find($state);
                    if ($holidayPackage) {
                        $set('total_amount', $holidayPackage->price);
                        $set('duration', $holidayPackage->duration);
                    }
                }),
            
            Select::make('payment_method_id')
                ->label('Payment Method')
                ->options(PaymentMethod::all()->pluck('name', 'id'))
                ->required()
                ->native(false)
                ->searchable(),
            TextInput::make('total_amount')
                ->label('Price')
                ->prefix('IDR')
                ->required()
                ->readOnly(),
            DatePicker::make('transaction_date')
                ->label('Date')
                ->timezone('Asia/Jakarta')
                ->required(),
            TextInput::make('duration')
                ->label('Duration')
                ->prefix('Days')
                ->required()
                ->readOnly(),
            Select::make('is_paid')
                ->label('Status')
                ->options([
                    false => 'Unpaid',
                    true => 'Paid',
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
                Tables\Columns\TextColumn::make('transaction_trx')
                    ->label('Transaction ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('User.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number'),
                Tables\Columns\TextColumn::make('HolidayPackage.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('PaymentMethod.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('HolidayPackage.duration')
                    ->label('Duration (Days)'),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->date(
                        'l, d/m/Y'
                    ),
                Tables\Columns\TextColumn::make('is_paid')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn (string $state): string => match ($state) {
                            'Unpaid' => 'danger',
                            'Paid' => 'success',
                            default => 'warning',
                        }
                    )
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('holiday_package_id')
                    ->label('Package')
                    ->options(HolidayPackage::all()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->action(function($record){
                        $record->update([
                            'is_paid' => true
                        ]);
                    })
                    ->requiresConfirmation()
                    ->color('success'),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
