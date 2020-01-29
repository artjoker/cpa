<?php

namespace Artjoker\Cpa\Providers\Credy\Lead;


use Artjoker\Cpa\Interfaces\Lead\LeadSource;
use Artjoker\Cpa\Lead\LeadInfo;
use Artjoker\Cpa\Traits\QueryParams;

class Parser implements \Artjoker\Cpa\Interfaces\Lead\LeadParser
{
    use QueryParams;

    protected const UTM_SOURCE     = 'credy';
    protected const TRANSACTION_ID = 'tid';

    public function parse(string $url): ?LeadInfo
    {
        $query = $this->getQueryParams($url);
        $isQueryValid = ($query['utm_source'] ?? null) === static::UTM_SOURCE
            && array_key_exists(static::TRANSACTION_ID, $query);

        if (!$isQueryValid) {
            return null;
        }

        return new LeadInfo(
            LeadSource::CREDY,
            [
                'tid' => $query[static::TRANSACTION_ID]
            ]
        );
    }
}