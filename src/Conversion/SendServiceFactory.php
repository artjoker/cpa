<?php


namespace Artjoker\Cpa\Conversion;

use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
use Artjoker\Cpa\Interfaces\Lead\LeadSource;
use Artjoker\Cpa\Providers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendServiceFactory
{
    /**
     * @var array
     */
    private $senders;
    /**
     * @var string
     */
    private $source;
    /**
     * @var string
     */
    private $event;

    /**
     * SendServiceFactory constructor.
     * @param string $source
     * @param string $event
     */
    public function __construct(string $source, string $event)
    {
        $this->senders = [
            LeadSource::SALES_DOUBLER => [
                'class'  => Providers\SalesDoubler\SendService::class,
                'config' => [
                    'class' => Providers\SalesDoubler\EnvironmentConfig::class,
                ],
            ],
            LeadSource::DO_AFFILIATE => [
                'class'  => Providers\DoAffiliate\SendService::class,
                'config' => [
                    'class' => Providers\DoAffiliate\EnvironmentConfig::class,
                ],
            ],
            LeadSource::LEADS_SU => [
                'class'  => Providers\LeadsSu\SendService::class,
                'config' => [
                    'class' => Providers\LeadsSu\EnvironmentConfig::class,
                ],
            ],
            LeadSource::FIN_LINE => [
                'class'  => Providers\FinLine\SendService::class,
                'config' => [
                    'class' => Providers\FinLine\EnvironmentConfig::class,
                ],
            ],
            LeadSource::FIN_ME => [
                'class'  => Providers\FinMe\SendService::class,
                'config' => [
                    'class' => Providers\FinMe\EnvironmentConfig::class,
                ],
            ],
            LeadSource::PAPA_KARLO => [
                'class'  => Providers\PapaKarlo\SendService::class,
                'config' => [
                    'class' => Providers\PapaKarlo\EnvironmentConfig::class,
                ],
            ],
            LeadSource::PDL_PROFIT => [
                'class'  => Providers\PdlProfit\SendService::class,
                'config' => [
                    'class' => Providers\PdlProfit\EnvironmentConfig::class,
                ],
            ],
            LeadSource::STORM_DIGITAL => [
                'class'  => Providers\StormDigital\SendService::class,
                'config' => [
                    'class' => Providers\StormDigital\EnvironmentConfig::class,
                ],
            ],
            LeadSource::ADMITAD => [
                'class'  => Providers\AdmitAd\SendService::class,
                'config' => [
                    'class' => Providers\AdmitAd\EnvironmentConfig::class,
                ],
            ],
            LeadSource::LEAD_GID => [
                'class'  => Providers\LeadGid\SendService::class,
                'config' => [
                    'class' => Providers\LeadGid\EnvironmentConfig::class,
                ],
            ],
            LeadSource::CREDY => [
                'class'  => Providers\Credy\SendService::class,
                'config' => [
                    'class' => Providers\Credy\EnvironmentConfig::class,
                ],
            ],
            LeadSource::LOANGATE => [
                'class'  => Providers\Loangate\SendService::class,
                'config' => [
                    'class' => Providers\Loangate\EnvironmentConfig::class,
                ],
            ],
            LeadSource::APPSCORP => [
                'class'  => Providers\Appscorp\SendService::class,
                'config' => [
                    'class' => Providers\Appscorp\EnvironmentConfig::class,
                ],
            ],
            LeadSource::PAP => [
                'class'  => Providers\Pap\SendService::class,
                'config' => [
                    'class' => Providers\Pap\EnvironmentConfig::class,
                ],
            ],
            LeadSource::GOOD_AFF => [
                'class'  => Providers\GoodAff\SendService::class,
                'config' => [
                    'class' => Providers\GoodAff\EnvironmentConfig::class,
                ],
            ],
            LeadSource::LET_ME_ADS => [
                'class'  => Providers\LetMeAds\SendService::class,
                'config' => [
                    'class' => Providers\LetMeAds\EnvironmentConfig::class,
                ],
            ],
            LeadSource::GURU_LEADS => [
                'class'  => Providers\GuruLeads\SendService::class,
                'config' => [
                    'class' => Providers\GuruLeads\EnvironmentConfig::class,
                ],
            ],
            LeadSource::CLICK2MONEY => [
                'class'  => Providers\Click2Money\SendService::class,
                'config' => [
                    'class' => Providers\Click2Money\EnvironmentConfig::class,
                ],
            ],
            LeadSource::NOLIMIT => [
                'class'  => Providers\Nolimit\SendService::class,
                'config' => [
                    'class' => Providers\Nolimit\EnvironmentConfig::class,
                ],
            ],
            LeadSource::MONEY_GO => [
                'class'  => Providers\MoneyGo\SendService::class,
                'config' => [
                    'class' => Providers\MoneyGo\EnvironmentConfig::class,
                ],
            ],
        ];
        $this->source = $source;
        $this->event = $event;
    }

    /**
     * @return \Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface|null
     * @var SendServiceInterface $sender
     */
    public function create()
    {
        if (!array_key_exists($this->source, $this->filteredSenders())) {
            Log::info("Trying to send conversion through disabled sender: $this->source");
            return null;
        }

        if ( !$this->hasEvent($this->source, $this->event) ) {
            Log::info("Event $this->event not found for $this->source");
            return null;
        }

        $config = app($this->senders[$this->source]['config']['class']);

        return app($this->senders[$this->source]['class'], compact('config'));
    }

    /**
     * @return array
     */
    private function filteredSenders()
    {
        return array_filter($this->senders, function ($sender, $source){
            return $this->shouldSend($source);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param $source
     * @param bool $default
     * @return mixed
     */
    private function shouldSend($source, $default = false)
    {
        $source = Str::snake($source);
        return Config::get('cpa.sources.' . $source, $default);
    }

    /**
     * @param $source
     * @param $event
     * @return bool
     */
    private function hasEvent($source, $event)
    {
        $source = Str::snake($source);
        return Config::has('cpa.events.' . $event . '.' . $source);
    }
}