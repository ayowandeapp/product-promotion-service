<?php

namespace App\Filter\Modifier\Factory;

use App\Filter\Modifier\PriceModifierInterface;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

class PriceModifierFactory implements PriceModifierFactoryInterface
{
    public function create(string $modifierType): PriceModifierInterface
    {
        //convert snake_case to pascal case
        $modClassBase = str_replace('_', '', ucwords($modifierType, '_'));

        $mod = self::PRICE_MODIFIER_NAMESPACE . $modClassBase;

        if (!class_exists($mod)) {
            throw new ClassNotFoundException($mod);
        }
        return new $mod();
    }
}
