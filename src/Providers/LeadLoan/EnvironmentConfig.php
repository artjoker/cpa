<?php

    namespace Artjoker\Cpa\Providers\LeadLoan;

    use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

    class EnvironmentConfig
    {
        use EnvironmentConfigTrait;

        public $keyPrefix = 'LEAD_LOAN_';

        public function getPath(?string $product = null): string
        {
            return env($this->appendProductPrefix('PATH', $product));
        }
    }