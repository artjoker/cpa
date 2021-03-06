<?php

namespace Artjoker\Cpa\Providers\PapaKarlo\Lead;


use Artjoker\Cpa\Interfaces\Lead\LeadParser;
use Artjoker\Cpa\Lead\LeadInfo;
use Artjoker\Cpa\Traits\QueryParams;

class Parser implements LeadParser
{
    use QueryParams;

    protected const UTM_SOURCE = 'papakarlo';
    protected const CLICK_ID   = 'clickid';

    public function parse(string $url): ?LeadInfo
    {
        $query = $this->getQueryParams($url);
        $isQueryValid = ($query['utm_source'] ?? null) === static::UTM_SOURCE
            && array_key_exists(static::CLICK_ID, $query);

        if (!$isQueryValid) {
            return null;
        }

        return new LeadInfo(
            \Artjoker\Cpa\Interfaces\Lead\LeadSource::PAPA_KARLO,
            [
                'clickId'  => $query[static::CLICK_ID],
                'utm_term' => $query['utm_term'] ?? null,
            ]
        );
    }
}