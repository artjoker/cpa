<?php

namespace Artjoker\Cpa\Facades;

use Artjoker\Cpa\Models\Conversion;
use Illuminate\Support\Facades\Facade;

/**
 * Class CpaConversion
 * @package Artjoker\Cpa\Facades
 * @method static Conversion register($user, string $conversionId, string $event)
 *
 * @see \Artjoker\Cpa\Conversion\ConversionService
 */
class CpaConversion extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cpaConversion';
    }
}
