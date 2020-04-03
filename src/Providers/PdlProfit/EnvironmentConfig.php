<?php

namespace Artjoker\Cpa\Providers\PdlProfit;

use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

class EnvironmentConfig
{
    use EnvironmentConfigTrait;

    public $keyPrefix = 'PDL_PROFIT_';

    public function getOffer(?string $product = null): int
    {
        return env($this->getProductPrefix($product) . 'OFFER', 0);
    }
}