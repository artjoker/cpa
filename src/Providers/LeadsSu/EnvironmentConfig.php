<?php

namespace Artjoker\Cpa\Providers\LeadsSu;

use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

class EnvironmentConfig
{
    use EnvironmentConfigTrait;

    public $keyPrefix = 'LEADS_SU_';

    public function getToken(?string $product = null): string
    {
        return env($this->getProductPrefix($product).'TOKEN');
    }

    public function getGoal(?string $product = null): int
    {
        return env($this->getProductPrefix($product).'GOAL');
    }
}