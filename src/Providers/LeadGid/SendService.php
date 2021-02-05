<?php

namespace Artjoker\Cpa\Providers\LeadGid;

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
     * @param EnvironmentConfig $config
     */
    public function __construct(EnvironmentConfig $config)
    {
        $this->config = $config;
        $this->source = LeadSource::LEAD_GID;
    }


    /**
     * @param Conversion $conversion
     * @param array      $params ['type' => 'offer|goal', 'offer_id' => 'numeric', 'goal_id' => 'numeric']
     *
     * @return Request
     */
    protected function getRequest(Conversion $conversion, array $params): Request
    {
        $advSub        = $conversion->getId();
        $transactionId = $conversion->getConfig()['click_id'] ?? null;

        $type    = $params['type'] ?? $this->config->getType($conversion->getProduct());
        $offerId = $params['offer_id'] ?? $this->config->getOfferId($conversion->getProduct());
        $goalId  = $params['goal_id'] ?? $this->config->getGoal($conversion->getProduct());
        $path    = ($type === 'offer') ? 'aff_lsr' : 'aff_goal';

        if ($type === 'offer') {
            $queryParams = http_build_query([
                'offer_id'       => $offerId,
                'adv_sub'        => $advSub,
                'transaction_id' => $transactionId,
            ]);
        } else {
            $queryParams = http_build_query([
                'a'              => 'lsr',
                'goal_id'        => $goalId,
                'adv_sub'        => $advSub,
                'transaction_id' => $transactionId,
            ]);
        }

        $url = "{$this->getDomain()}/{$path}?{$queryParams}";

        return new Request('get', $url);
    }
}