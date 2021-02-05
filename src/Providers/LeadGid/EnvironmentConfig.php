<?php

namespace Artjoker\Cpa\Providers\LeadGid;

use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

class EnvironmentConfig
{
    use EnvironmentConfigTrait;

    public $keyPrefix = 'LEAD_GID_';

    public function getOfferId(?string $product = null): int
    {
        return env($this->getProductPrefix($product) . 'OFFER_ID', 0);
    }

    public function getGoal(?string $product = null): ?int
    {
        return env($this->getProductPrefix($product) . 'GOAL_ID');
    }

    public function getType(?string $product = null): ?string
    {
        return env($this->getProductPrefix($product) . 'TYPE');
    }
}