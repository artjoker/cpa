<?php

    namespace Artjoker\Cpa\Providers\FinMe;

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
            $this->source = LeadSource::FIN_ME;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $clickId  = $conversion->getConfig()['clickId'] ?? null;
            $actionId = $conversion->getId();

            $goal   = $params['goal'] ?? null;

            $queryParams = http_build_query([
                'clickid'   => $clickId,
                'action_id' => $actionId,
                'goal'      => $goal,
            ]);

            $customParams = '';
            if (!empty($params['custom_params'])) {
                $customParams = '&' . http_build_query($params['custom_params']);
            }

            $url = "{$this->getDomain()}/postback?{$queryParams}{$customParams}";

            return new Request('get', $url);
        }
    }