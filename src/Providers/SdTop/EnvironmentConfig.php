<?php

    namespace Artjoker\Cpa\Providers\SdTop;

    use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

    class EnvironmentConfig
    {
        use EnvironmentConfigTrait;

        public $keyPrefix = 'SD_TOP_';

        public function getId(?string $product = null): int
        {
            return env($this->getProductPrefix($product) . 'ID', 0);
        }

        public function getToken(?string $product = null): string
        {
            return env($this->getProductPrefix($product) . 'TOKEN', '');
        }
    }