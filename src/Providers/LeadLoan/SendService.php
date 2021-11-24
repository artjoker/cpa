<?php

    namespace Artjoker\Cpa\Providers\LeadLoan;

    use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
    use Artjoker\Cpa\Interfaces\Lead\LeadSource;
    use Artjoker\Cpa\Models\Conversion;
    use Artjoker\Cpa\Traits\SendServiceTrait;
    use GuzzleHttp\Psr7\Request;

    class SendService implements SendServiceInterface
    {
        use SendServiceTrait;

        /**
         * @var EnvironmentConfig
         */
        protected $config;

        /**
         * SendService constructor.
         *
         * @param EnvironmentConfig $config
         */
        public function __construct(EnvironmentConfig $config)
        {
            $this->config = $config;
            $this->source = LeadSource::LEAD_LOAN;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $clickId      = $conversion->getConfig()['click_id'] ?? null;
            $conversionId = $conversion->getId();

            $path = $params['path'] ?? $this->config->getPath($conversion->getProduct());
            $goal = $params['goal'] ?? null;

            $queryParams = http_build_query([
                'clickid'   => $clickId,
                'action_id' => $conversionId,
                'goal'      => $goal,
            ]);

            $url = "{$this->getDomain()}/{$path}?{$queryParams}";

            return new Request('get', $url);
        }
    }
