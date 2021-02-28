<?php

    namespace Artjoker\Cpa\Providers\Credy;

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
            $this->source = LeadSource::CREDY;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $transactionId = $conversion->getConfig()['transaction_id'] ?? null;
            $offer         = $offerId = $params['offer_id'] ?? $this->config->getOffer($conversion->getProduct());
            $goalId        = $params['goal_id'] ?? $this->config->getGoal($conversion->getProduct());
            $conversionId  = $conversion->getId();

            $type = $params['type'] ?? $this->config->getType($conversion->getProduct());
            $path = ($type === 'offer') ? 'aff_lsr' : 'aff_goal';

            if ($type === 'offer') {
                $queryParams = http_build_query([
                    'offer_id'       => $offer,
                    'transaction_id' => $transactionId,
                    'adv_sub'        => $conversionId,
                ]);
            } else {
                $queryParams = http_build_query([
                    'a'              => 'lsr',
                    'goal_id'        => $goalId,
                    'transaction_id' => $transactionId,
                    'adv_sub'        => $conversionId,
                ]);
            }

            $url = "{$this->getDomain()}/{$path}?{$queryParams}";

            return new Request('get', $url);
        }
    }