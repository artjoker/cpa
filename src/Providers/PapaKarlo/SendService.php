<?php

namespace Artjoker\Cpa\Providers\PapaKarlo;

use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
use Artjoker\Cpa\Interfaces\Lead\LeadSource;
use Artjoker\Cpa\Models\Conversion;
use Artjoker\Cpa\Traits\SendServiceTrait;
use GuzzleHttp\Psr7\Request;

class SendService implements SendServiceInterface
{
    use SendServiceTrait;

    public $source = LeadSource::PAPA_KARLO;

    /**
     * @var EnvironmentConfig
     */
    protected $config;

    /**
     * SendService constructor.
     * @param  EnvironmentConfig  $config
     */
    public function __construct(EnvironmentConfig $config)
    {
        $this->config = $config;
    }


    /**
     * @param  Conversion  $conversion
     * @param  array  $params  ['type' => 'offer|goal', 'offer_id' => 'numeric', 'goal_id' => 'numeric']
     * @return Request
     */
    public function getRequest(Conversion $conversion, array $params): Request
    {
        $clickId = $conversion->getConfig()['clickId'] ?? null;
        $transId = $conversion->getId();
        $type    = $params['type'] ?? $this->config->getType($conversion->getProduct());
        $offerId = $params['offer_id'] ?? $this->config->getOffer($conversion->getProduct());
        $goalId  = $params['goal_id'] ?? $this->config->getGoal($conversion->getProduct());
        $path    = ($type === 'offer') ? 'aff_lsr' : 'aff_goal';

        $queryParams = http_build_query([
            'a'              => 'lsr',
            'offer_id'       => $offerId,
            'goal_id'        => $goalId,
            'adv_sub'        => $transId,
            'transaction_id' => $clickId,
        ]);

        $url = "{$this->getDomain()}/{$path}?{$queryParams}";

        return new Request('get', $url);
    }
}