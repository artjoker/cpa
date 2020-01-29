<?php

namespace Artjoker\Cpa\Providers\FinLine;

use Artjoker\Cpa\Traits\EnvironmentConfigTrait;

class EnvironmentConfig
{
    use EnvironmentConfigTrait;

    public $keyPrefix = 'FIN_LINE_';

}