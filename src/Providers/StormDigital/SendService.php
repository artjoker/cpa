<?php

    namespace Artjoker\Cpa\Providers\StormDigital;

    use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
    use Artjoker\Cpa\Interfaces\Lead\LeadSource;
    use Artjoker\Cpa\Models\Conversion;
    use Artjoker\Cpa\Traits\SendServiceTrait;
    use GuzzleHttp\Psr7\Request;

    class SendService implements SendServiceInterface
    {
        use SendServiceTrait;

        public const PATH_POSTBACK = 'postback';

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
            $this->source = LeadSource::STORM_DIGITAL;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $clickId  = $conversion->getConfig()['clickId'] ?? null;
            $actionId = $conversion->getId();

            $goal   = $params['goal'] ?? $this->config->getGoal($conversion->getProduct());
            $path   = $params['path'] ?? self::PATH_POSTBACK;

            $queryParams = http_build_query([
                'clickid'   => $clickId,
                'goal'      => $goal,
                'action_id' => $actionId,
            ]);

            $customParams = '';
            if (!empty($params['custom_params'])) {
                $customParams = '&' . http_build_query($params['custom_params']);
            }

            $url = "{$this->getDomain()}/{$path}?{$queryParams}{$customParams}";

            return new Request('get', $url);
        }
    }