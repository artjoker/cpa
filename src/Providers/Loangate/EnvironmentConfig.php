<?php

namespace Artjoker\Cpa\Providers\Loangate;

use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

class EnvironmentConfig
{
    use EnvironmentConfigTrait;

    public $keyPrefix = 'LOANGATE_';

    public function getSecure(?string $product = null): int
    {
        return env($this->getProductPrefix($product) . 'SECURE', 0);
    }

}