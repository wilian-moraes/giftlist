<?php

namespace App;

use App\Traits\EnumToArray;

enum UserType: int
{
    case Host = 1;
    case Guest = 2;
    case Other = 3;

    public function label(): string
    {
        return match ($this) {
            self::Host => "Anfitrião",
            self::Guest => "Convidado",
            self::Other => "Outro",
        };
    }
}

