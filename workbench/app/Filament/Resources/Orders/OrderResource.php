<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Resources\Orders;

use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Workbench\App\Filament\Resources\Orders\Pages\ListOrders;
use Workbench\App\Models\Order;

final class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    public static function getModelLabel(): string
    {
        return __('demo::demo.order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('demo::demo.orders');
    }

    public static function getNavigationGroup(): string
    {
        return __('demo::demo.sales');
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('id')]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
        ];
    }
}
