<?php

    namespace Artjoker\Cpa\Providers\LetMeAds;

    use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

    class EnvironmentConfig
    {
        use EnvironmentConfigTrait;

        public $keyPrefix = 'LET_ME_ADS_';

        public function getPath(?string $product = null): string
        {
            return env($this->appendProductPrefix('PATH', $product));
        }
    }