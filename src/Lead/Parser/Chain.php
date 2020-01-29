<?php

namespace Artjoker\Cpa\Lead\Parser;

use Artjoker\Cpa\Interfaces\Lead\LeadParser;
use Artjoker\Cpa\Lead\LeadInfo;

class Chain implements LeadParser
{

    public $parsers;

    /**
     * Chain constructor.
     */
    public function __construct()
    {
        $this->parsers = (new ParsersFactory())->create();
    }

    public function parse(string $url): ?LeadInfo
    {
        foreach ($this->parsers as $parser) {
            $leadInfo = $parser->parse($url);
            if ($leadInfo instanceof LeadInfo) {
                return $leadInfo;
            }
        }

        return null;
    }
}