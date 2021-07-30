<?php

    namespace Artjoker\Cpa\Providers\Nolimit;

    use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

    class EnvironmentConfig
    {
        use EnvironmentConfigTrait;

        public $keyPrefix = 'NOLIMIT_';

        public function getPath(?string $product = null): string
        {
            return env($this->appendProductPrefix('PATH', $product));
        }
    }