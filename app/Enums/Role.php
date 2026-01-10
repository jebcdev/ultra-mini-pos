<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Role: string implements HasColor, HasLabel
{
    case super_admin = 'super_admin';
    case admin = 'admin';
    case user = 'user';

    public static function names(): array
    {
        return array_map(fn($case) => $case->name, self::cases());
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function getColor(): string
    {
        return match ($this) {
            self::super_admin => 'danger',
            self::admin => 'info',
            self::user => 'success',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::super_admin => 'Super Admin',
            self::admin => 'Admin',
            self::user => 'User',
        };
    }
}
