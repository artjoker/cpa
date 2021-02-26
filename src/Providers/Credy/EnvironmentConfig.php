<?php

    namespace Artjoker\Cpa\Providers\Credy;

    use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

    class EnvironmentConfig
    {
        use EnvironmentConfigTrait;

        public $keyPrefix = 'CREDY_';

        public function getOffer(?string $product = null): string
        {
            return env($this->getProductPrefix($product) . 'OFFER');
        }

        public function getType(?string $product = null): ?string
        {
            return env($this->getProductPrefix($product) . 'TYPE');
        }

        public function getGoal(?string $product = null): ?int
        {
            return env($this->getProductPrefix($product) . 'GOAL_ID');
        }
    }