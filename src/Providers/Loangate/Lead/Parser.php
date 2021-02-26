<?php

    namespace Artjoker\Cpa\Providers\Loangate\Lead;


    use Artjoker\Cpa\Interfaces\Lead\LeadParser;
    use Artjoker\Cpa\Interfaces\Lead\LeadSource;
    use Artjoker\Cpa\Lead\LeadInfo;
    use Artjoker\Cpa\Traits\QueryParams;

    class Parser implements LeadParser
    {
        use QueryParams;

        protected const UTM_SOURCES = ['loangate'];
        protected const CLICK_ID    = 'afclick';

        public function parse(string $url): ?LeadInfo
        {
            $query        = $this->getQueryParams($url);
            $isQueryValid = in_array(($query['utm_source'] ?? null), static::UTM_SOURCES, true)
                && array_key_exists(static::CLICK_ID, $query);

            if (!$isQueryValid) {
                return null;
            }

            return new LeadInfo(
                LeadSource::LOANGATE,
                [
                    'afclick' => $query[static::CLICK_ID],
                ]
            );
        }
    }