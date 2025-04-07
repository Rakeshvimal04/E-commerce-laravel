<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\select;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('order Information')->schema([
                        Select::make('user_id')
                        ->label('customer')
                        ->relationship('user','name')
                        ->searchable()
                        ->preload()
                        ->required(),

                        Select::make('payment_method')
                        ->options([
                            'COD' => 'cash on delivery',
                            'stripe'=>'stripe'
                        ])
                        ->required(),

                        Select::make('payment_status')
                        ->options([
                            'pending' => 'pending',
                            'success' => 'success',
                            'failed' => 'failed'
                        ])
                        ->default('pending')
                        ->required(),
                        ToggleButtons::make('status')
                        ->inline()
                        ->default('new')
                        ->required()
                        ->options([
                            'new'=>'New',
                            'processing'=>'Processing',
                            'shipped'=>'Shipped',
                            'delivered'=>'Delivered',
                            'cancelled'=>'Cancelled'
                        ])
                        ->colors([
                            'new'=>'info',
                            'processing'=>'warning',
                            'shipped'=>'success',
                            'delivered'=>'success',
                            'cancelled'=>'danger'
                        ])
                        ->icons([
                            'new'=>'heroicon-m-sparkles',
                            'processing'=>'heroicon-m-arrow-path',
                            'shipped'=>'heroicon-m-truck',
                            'delivered'=>'heroicon-m-check-badge',
                            'cancelled'=>'heroicon-m-x-circle'
                        ]),
                        Select::make('currency')
                        ->options([
                            'inr'=>'INR',
                            'usd'=>'USD',
                            'eur'=>'EUR',
                            'gbp'=>'GBP'
                        ])
                        ->default('inr')
                        ->required(),
                        Select::make('shipping_method')
                        ->options([
                            'fedex'=>'FedEx',
                            'ups'=>'UPS',
                            'dhl'=>'DHL',
                            'usps'=>'USPS'
                        ]),

                            Textarea::make('notes')
                            ->columnSpanFull()
                    ])->columns(2),

                    Section::make('order items')->schema([
                        Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                            ->relationship('product','name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->columnSpan(4),

                            TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->columnSpan(2),

                            TextInput::make('unit_amount')
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->columnSpan(3),

                            TextInput::make('total_amount')
                            ->required()
                            ->numeric()
                            ->columnSpan(3)
                        ])->columns(12)
                    ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
