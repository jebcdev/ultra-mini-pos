<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InvoiceStatus: string implements HasColor, HasLabel
{
    case pending = 'pending';
    case paid = 'paid';
    case overdue = 'overdue';
    case cancelled = 'cancelled';

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
            self::pending => 'warning',
            self::paid => 'success',
            self::overdue => 'danger',
            self::cancelled => 'gray',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::pending => 'Pending',
            self::paid => 'Paid',
            self::overdue => 'Overdue',
            self::cancelled => 'Cancelled',
        };
    }
}
