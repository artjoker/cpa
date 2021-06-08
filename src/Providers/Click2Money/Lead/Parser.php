<?php

    namespace Artjoker\Cpa\Providers\Click2Money\Lead;

    use Artjoker\Cpa\Lead\LeadInfo;
    use Artjoker\Cpa\Traits\QueryParams;

    class Parser implements \Artjoker\Cpa\Interfaces\Lead\LeadParser
    {
        use QueryParams;

        protected const UTM_SOURCE = 'c2m';
        protected const CLICK_ID   = 'click_id';

        public function parse(string $url): ?LeadInfo
        {
            $query        = $this->getQueryParams($url);
            $isQueryValid = ($query['utm_source'] ?? null) === static::UTM_SOURCE
                && array_key_exists(static::CLICK_ID, $query);

            if (!$isQueryValid) {
                return null;
            }

            return new LeadInfo(
                \Artjoker\Cpa\Interfaces\Lead\LeadSource::CLICK2MONEY,
                [
                    'click_id'     => $query[static::CLICK_ID],
                    'utm_medium'   => $query['utm_medium'] ?? null,
                    'utm_campaign' => $query['utm_campaign'] ?? null,
                ]
            );
        }
    }
