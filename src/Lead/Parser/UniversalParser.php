<?php

namespace App\Lead\Parser;

use App\Models\CpaNetwork;
use Artjoker\Cpa\Interfaces\Lead\LeadParser;
use Artjoker\Cpa\Lead\LeadInfo;
use Artjoker\Cpa\Traits\QueryParams;

class UniversalParser implements LeadParser
{
    use QueryParams;

    public function parse(string $url): ?LeadInfo
    {
        $query = $this->getQueryParams($url);
        $utm_source = $query['utm_source'] ?? null;

        if (!$utm_source) {
            return null;
        }

        $network = CpaNetwork::where('slug', $utm_source)->where('is_active', true)->first();
        if (!$network) {
            return null;
        }

        return new LeadInfo(
            $network->slug,
            collect($query)->except('utm_source')->toArray()
        );
    }
} 