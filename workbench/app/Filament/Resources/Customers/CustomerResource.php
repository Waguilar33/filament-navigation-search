<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Resources\Customers;

use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Workbench\App\Filament\Resources\Customers\Pages\ListCustomers;
use Workbench\App\Models\User;

final class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function getNavigationLabel(): string
    {
        return __('demo::demo.customers');
    }

    public static function getModelLabel(): string
    {
        return __('demo::demo.client');
    }

    public static function getNavigationGroup(): string
    {
        return __('demo::demo.sales');
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('name')]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
        ];
    }
}
