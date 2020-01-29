<?php

namespace Artjoker\Cpa\Interfaces\Conversion;

use Artjoker\Cpa\Models\Conversion;

interface ServiceInterface
{
    public function register($model, string $conversionId, string $event): ?Conversion;
}