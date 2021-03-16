<?php

    namespace Artjoker\Cpa\Providers\FinMe;

    use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

    class EnvironmentConfig
    {
        use EnvironmentConfigTrait;

        public $keyPrefix = 'FIN_ME_';

    }