<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UnitOfMeasure: string implements HasColor,HasLabel
{
    case unit = 'unit'; // Unidad
    case piece = 'piece'; // Pieza
    case kilogram = 'kilogram'; // Kilogramo
    case gram = 'gram'; // Gramo
    case liter = 'liter'; // Litro
    case milliliter = 'milliliter'; // Mililitro
    case meter = 'meter'; // Metro
    case centimeter = 'centimeter'; // Centímetro
    case millimeter = 'millimeter'; // Milímetro
    case inch = 'inch'; // Pulgada
    case foot = 'foot'; // Pie
    case yard = 'yard'; // Yarda
    case pound = 'pound'; // Libra
    case ounce = 'ounce'; // Onza
    case ton = 'ton'; // Tonelada
    case box = 'box'; // Caja
    case pack = 'pack'; // Paquete
    case dozen = 'dozen'; // Docena
    case bottle = 'bottle'; // Botella
    case can = 'can'; // Lata
    case roll = 'roll'; // Rollo
    case sheet = 'sheet'; // Hoja
    case pair = 'pair'; // Par
    case set = 'set'; // Conjunto
    case carton = 'carton'; // Cartón
    case pallet = 'pallet'; // Palé
    case bundle = 'bundle'; // Atado
    case square_meter = 'square_meter'; // Metro Cuadrado
    case square_foot = 'square_foot'; // Pie Cuadrado
    case cubic_meter = 'cubic_meter'; // Metro Cúbico
    case cubic_foot = 'cubic_foot'; // Pie Cúbico

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
            self::unit, self::piece, self::box, self::pack, self::dozen, self::bottle, self::can, self::roll, self::sheet, self::pair, self::set, self::carton, self::pallet, self::bundle => 'primary',
            self::kilogram, self::gram, self::liter, self::milliliter => 'success',
            self::meter, self::centimeter, self::millimeter, self::inch, self::foot, self::yard => 'info',
            self::pound, self::ounce, self::ton => 'warning',
            self::square_meter, self::square_foot, self::cubic_meter, self::cubic_foot => 'secondary',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::unit => __('Unit'),
            self::piece => __('Piece'),
            self::kilogram => __('Kilogram'),
            self::gram => __('Gram'),
            self::liter => __('Liter'),
            self::milliliter => __('Milliliter'),
            self::meter => __('Meter'),
            self::centimeter => __('Centimeter'),
            self::millimeter => __('Millimeter'),
            self::inch => __('Inch'),
            self::foot => __('Foot'),
            self::yard => __('Yard'),
            self::pound => __('Pound'),
            self::ounce => __('Ounce'),
            self::ton => __('Ton'),
            self::box => __('Box'),
            self::pack => __('Pack'),
            self::dozen => __('Dozen'),
            self::bottle => __('Bottle'),
            self::can => __('Can'),
            self::roll => __('Roll'),
            self::sheet => __('Sheet'),
            self::pair => __('Pair'),
            self::set => __('Set'),
            self::carton => __('Carton'),
            self::pallet => __('Pallet'),
            self::bundle => __('Bundle'),
            self::square_meter => __('Square Meter'),
            self::square_foot => __('Square Foot'),
            self::cubic_meter => __('Cubic Meter'),
            self::cubic_foot => __('Cubic Foot'),
        };
    }
}
