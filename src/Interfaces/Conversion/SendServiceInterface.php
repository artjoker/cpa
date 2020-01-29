<?php

namespace Artjoker\Cpa\Interfaces\Conversion;

use Artjoker\Cpa\Conversion\Postback;
use Artjoker\Cpa\Models\Conversion;

interface SendServiceInterface
{
    public function send(Conversion $conversion, array $params): Postback;

    public function getDomain(): string;

    public function getSource(): string;
}