<?php

namespace Artjoker\Cpa\Providers\DoAffiliate;

use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

class EnvironmentConfig
{
    use EnvironmentConfigTrait;

    public $keyPrefix = 'DO_AFFILIATE_';

    public function getPath(?string $product = null): string
    {
        return env($this->appendProductPrefix('PATH', $product));
    }
}