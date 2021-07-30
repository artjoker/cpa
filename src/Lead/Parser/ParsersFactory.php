<?php


    namespace Artjoker\Cpa\Lead\Parser;

    use Artjoker\Cpa;
    use Artjoker\Cpa\Interfaces\Lead\LeadParser;
    use Artjoker\Cpa\Interfaces\Lead\LeadSource;
    use Illuminate\Support\Facades\Config;
    use Illuminate\Support\Str;

    class ParsersFactory
    {
        /**
         * @var array
         */
        private $parsers;

        /**
         * ParserFactory constructor.
         */
        public function __construct()
        {
            $this->parsers = [
                LeadSource::ADMITAD       => Cpa\Providers\AdmitAd\Lead\Parser::class,
                LeadSource::CREDY         => Cpa\Providers\Credy\Lead\Parser::class,
                LeadSource::DO_AFFILIATE  => Cpa\Providers\DoAffiliate\Lead\Parser::class,
                LeadSource::FIN_LINE      => Cpa\Providers\FinLine\Lead\Parser::class,
                LeadSource::FIN_ME        => Cpa\Providers\FinMe\Lead\Parser::class,
                LeadSource::LEAD_GID      => Cpa\Providers\LeadGid\Lead\Parser::class,
                LeadSource::LEADS_SU      => Cpa\Providers\LeadsSu\Lead\Parser::class,
                LeadSource::PAPA_KARLO    => Cpa\Providers\PapaKarlo\Lead\Parser::class,
                LeadSource::PDL_PROFIT    => Cpa\Providers\PdlProfit\Lead\Parser::class,
                LeadSource::SALES_DOUBLER => Cpa\Providers\SalesDoubler\Lead\Parser::class,
                LeadSource::STORM_DIGITAL => Cpa\Providers\StormDigital\Lead\Parser::class,
                LeadSource::LOANGATE      => Cpa\Providers\Loangate\Lead\Parser::class,
                LeadSource::APPSCORP      => Cpa\Providers\Appscorp\Lead\Parser::class,
                LeadSource::PAP           => Cpa\Providers\Pap\Lead\Parser::class,
                LeadSource::GOOD_AFF      => Cpa\Providers\GoodAff\Lead\Parser::class,
                LeadSource::LET_ME_ADS    => Cpa\Providers\LetMeAds\Lead\Parser::class,
                LeadSource::GURU_LEADS    => Cpa\Providers\GuruLeads\Lead\Parser::class,
                LeadSource::CLICK2MONEY   => Cpa\Providers\Click2Money\Lead\Parser::class,
                LeadSource::NOLIMIT       => Cpa\Providers\Nolimit\Lead\Parser::class,
                // add all needed parsers here
            ];
        }

        /**
         * @return array
         */
        public function create()
        {
            return array_map(static function ($parser): LeadParser {
                return app()->make($parser);
            }, $this->filteredParsers());
        }

        /**
         * @return array
         */
        private function filteredParsers()
        {
            return array_filter($this->parsers, function ($parser, $source) {
                return $this->shouldParse($source);
            }, ARRAY_FILTER_USE_BOTH);
        }

        /**
         * @param      $source
         * @param bool $default
         *
         * @return mixed
         */
        private function shouldParse($source, $default = false)
        {
            $source = Str::snake($source);
            return Config::get('cpa.sources.' . $source, $default);
        }
    }