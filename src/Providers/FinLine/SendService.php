<?php

namespace Artjoker\Cpa\Providers\FinLine;

use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
use Artjoker\Cpa\Interfaces\Lead\LeadSource;
use Artjoker\Cpa\Models\Conversion;
use Artjoker\Cpa\Traits\SendServiceTrait;
use GuzzleHttp\Psr7\Request;

class SendService implements SendServiceInterface
{
    use SendServiceTrait;

    public const STATUS_APPROVED = 1;
    public const STATUS_PENDING  = 2;
    public const STATUS_DECLINED = 3;

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
        $this->source = LeadSource::FIN_LINE;
    }


    protected function getRequest(Conversion $conversion, array $params): Request
    {
        $clickId  = $conversion->getConfig()['clickId'] ?? null;
        $actionId = $conversion->getId();

        $goal = $params['goal'] ?? null;
        $status = $params['status'] ?? self::STATUS_APPROVED;

        $queryParams = http_build_query([
            'clickid'   => $clickId,
            'action_id' => $actionId,
            'goal'      => $goal,
            'status'    => $status ?? self::STATUS_PENDING,
        ]);

        $url = "{$this->getDomain()}/postback?{$queryParams}";

        return new Request('get', $url);
    }
}