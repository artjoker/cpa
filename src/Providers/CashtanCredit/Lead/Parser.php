<?php

    namespace Artjoker\Cpa\Providers\CashtanCredit\Lead;


    use Artjoker\Cpa\Lead\LeadInfo;
    use Artjoker\Cpa\Traits\QueryParams;
    use Illuminate\Support\Facades\Config;

    class Parser implements \Artjoker\Cpa\Interfaces\Lead\LeadParser
    {
        use QueryParams;

        public function parse(string $url): ?LeadInfo
        {
            $utm_sources = Config::get('cpa.cashtan_credit.utm_sources');
            $query        = $this->getQueryParams($url);
            $isQueryValid = in_array($query['utm_source'] ?? null, $utm_sources, true);

            if (!$isQueryValid) {
                return null;
            }

            return new LeadInfo(
                \Artjoker\Cpa\Interfaces\Lead\LeadSource::CASHTAN_CREDIT,
                collect($query)->except('utm_source')->toArray()
            );
        }
    }