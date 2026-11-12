<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Pages;

use Filament\Pages\Page;

final class Payroll extends Page
{
    public static function canAccess(): bool
    {
        return false;
    }
}
