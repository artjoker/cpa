<?php

namespace Artjoker\Cpa\Providers\StormDigital\Lead;


use Artjoker\Cpa\Lead\LeadInfo;
use Artjoker\Cpa\Traits\QueryParams;

class Parser implements \Artjoker\Cpa\Interfaces\Lead\LeadParser
{
    use QueryParams;

    protected const UTM_SOURCES = [
        'stormdigital',
        'storm',
    ];
    protected const CLICK_ID = 'transaction_id';
    protected const AFF_ID   = 'aff_id';

    public function parse(string $url): ?LeadInfo
    {
        $query = $this->getQueryParams($url);
        $isQueryValid = in_array($query['utm_source'] ?? null, static::UTM_SOURCES, true)
            && array_key_exists(static::CLICK_ID, $query);

        if (!$isQueryValid) {
            return null;
        }

        return new LeadInfo(
            \Artjoker\Cpa\Interfaces\Lead\LeadSource::STORM_DIGITAL,
            [
                'clickId' => $query[static::CLICK_ID],
                'aid'     => $query[static::AFF_ID] ?? $query['utm_campaign'] ?? null,
            ]
        );
    }
}