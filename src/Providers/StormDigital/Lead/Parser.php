<?php

namespace Artjoker\Cpa\Providers\StormDigital\Lead;


use Artjoker\Cpa\Lead\LeadInfo;
use Artjoker\Cpa\Traits\QueryParams;

class Parser implements \Artjoker\Cpa\Interfaces\Lead\LeadParser
{
    use QueryParams;

    protected const UTM_SOURCE = 'stormdigital';
    protected const AFF_SUB    = 'aff_sub';
    protected const AFF_ID     = 'aff_id';

    public function parse(string $url): ?LeadInfo
    {
        $query = $this->getQueryParams($url);
        $isQueryValid = ($query['utm_source'] ?? null) === static::UTM_SOURCE
            && array_key_exists(static::AFF_SUB, $query);

        if (!$isQueryValid) {
            return null;
        }

        return new LeadInfo(
            \Artjoker\Cpa\Interfaces\Lead\LeadSource::STORM_DIGITAL,
            [
                'clickId' => $query[static::AFF_SUB],
                'pid'     => $query[static::AFF_ID] ?? null,
            ]
        );
    }
}