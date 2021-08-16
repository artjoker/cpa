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
        $this->source = LeadSource::STORM_DIGITAL;
    }


    protected function getRequest(Conversion $conversion, array $params): Request
    {
        $clickId  = $conversion->getConfig()['clickId'] ?? null;
        $actionId = $conversion->getId();
        $secure   = $this->config->getSecure($conversion->getProduct());
        $goal = $params['goal'] ?? $this->config->getGoal($conversion->getProduct());

        $queryParams = http_build_query([
            'clickid'   => $clickId,
            'action_id' => $actionId,
            'goal'      => $goal,
            'secure'    => $secure,
        ]);

        $customParams = '';
        if (!empty($params['custom_params'])) {
            $customParams = '&' . http_build_query($params['custom_params']);
        }

        $url = "{$this->getDomain()}/postback?{$queryParams}{$customParams}";

        return new Request('get', $url);
    }
}