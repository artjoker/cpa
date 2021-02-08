<?php

    namespace Artjoker\Cpa\Providers\Loangate;

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
            $this->source = LeadSource::LOANGATE;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $clickId  = $conversion->getConfig()['afclick'] ?? null;
            $actionId = $conversion->getId();
            $secure   = $params['secure'] ?? $this->config->getSecure($conversion->getProduct());
            $goal     = $params['goal'] ?? null;

            $queryParams = http_build_query([
                'clickid'   => $clickId,
                'action_id' => $actionId,
                'goal'      => $goal,
                'secure'    => $secure,
            ]);

            $url = "{$this->getDomain()}/postback?{$queryParams}";

            return new Request('get', $url);
        }
    }