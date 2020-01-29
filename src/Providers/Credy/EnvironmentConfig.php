<?php

namespace Artjoker\Cpa\Providers\Credy;

use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

class EnvironmentConfig
{
    use EnvironmentConfigTrait;

    public $keyPrefix = 'CREDY_';

    public function getOffer(?string $product = null): string
    {
        return env($this->getProductPrefix($product).'OFFER');
    }
}