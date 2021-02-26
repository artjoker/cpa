<?php

    namespace Artjoker\Cpa\Providers\Squidleads;

    use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

    class EnvironmentConfig
    {
        use EnvironmentConfigTrait;

        public $keyPrefix = 'SQUID_LEADERS_';

        public function getAccountId(?string $product = null): int
        {
            return env($this->getProductPrefix($product) . 'ACCOUNT_ID', 0);
        }
    }