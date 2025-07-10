<?php


    namespace Artjoker\Cpa\Conversion;

    use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
    use Artjoker\Cpa\Interfaces\Lead\LeadSource;
    use Artjoker\Cpa\Providers;
    use Illuminate\Support\Facades\Config;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Str;
    use Artjoker\Cpa\Repositories\CpaNetworkRepository;
    use Artjoker\Cpa\Conversion\UniversalSendService;
    use Artjoker\Cpa\Models\CpaNetwork;

    class SendServiceFactory
    {
        /**
         * @var array
         */
        private $senders;
        /**
         * @var CpaNetworkRepository
         */
        private $network_repository;
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
         *
         * @param string $source
         * @param string $event
         * @param CpaNetworkRepository|null $network_repository
         */
        public function __construct(string $source, string $event, CpaNetworkRepository $network_repository = null)
        {
            $this->network_repository = $network_repository ?? app(CpaNetworkRepository::class);
            $this->senders = [
                LeadSource::SALES_DOUBLER  => [
                    'class'  => Providers\SalesDoubler\SendService::class,
                    'config' => [
                        'class' => Providers\SalesDoubler\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::DO_AFFILIATE   => [
                    'class'  => Providers\DoAffiliate\SendService::class,
                    'config' => [
                        'class' => Providers\DoAffiliate\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::LEADS_SU       => [
                    'class'  => Providers\LeadsSu\SendService::class,
                    'config' => [
                        'class' => Providers\LeadsSu\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::FIN_LINE       => [
                    'class'  => Providers\FinLine\SendService::class,
                    'config' => [
                        'class' => Providers\FinLine\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::FIN_ME         => [
                    'class'  => Providers\FinMe\SendService::class,
                    'config' => [
                        'class' => Providers\FinMe\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::PAPA_KARLO     => [
                    'class'  => Providers\PapaKarlo\SendService::class,
                    'config' => [
                        'class' => Providers\PapaKarlo\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::PDL_PROFIT     => [
                    'class'  => Providers\PdlProfit\SendService::class,
                    'config' => [
                        'class' => Providers\PdlProfit\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::STORM_DIGITAL  => [
                    'class'  => Providers\StormDigital\SendService::class,
                    'config' => [
                        'class' => Providers\StormDigital\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::ADMITAD        => [
                    'class'  => Providers\AdmitAd\SendService::class,
                    'config' => [
                        'class' => Providers\AdmitAd\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::LEAD_GID       => [
                    'class'  => Providers\LeadGid\SendService::class,
                    'config' => [
                        'class' => Providers\LeadGid\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::CREDY          => [
                    'class'  => Providers\Credy\SendService::class,
                    'config' => [
                        'class' => Providers\Credy\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::LOANGATE       => [
                    'class'  => Providers\Loangate\SendService::class,
                    'config' => [
                        'class' => Providers\Loangate\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::APPSCORP       => [
                    'class'  => Providers\Appscorp\SendService::class,
                    'config' => [
                        'class' => Providers\Appscorp\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::PAP            => [
                    'class'  => Providers\Pap\SendService::class,
                    'config' => [
                        'class' => Providers\Pap\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::GOOD_AFF       => [
                    'class'  => Providers\GoodAff\SendService::class,
                    'config' => [
                        'class' => Providers\GoodAff\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::LET_ME_ADS     => [
                    'class'  => Providers\LetMeAds\SendService::class,
                    'config' => [
                        'class' => Providers\LetMeAds\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::GURU_LEADS     => [
                    'class'  => Providers\GuruLeads\SendService::class,
                    'config' => [
                        'class' => Providers\GuruLeads\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::CLICK2MONEY    => [
                    'class'  => Providers\Click2Money\SendService::class,
                    'config' => [
                        'class' => Providers\Click2Money\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::NOLIMIT        => [
                    'class'  => Providers\Nolimit\SendService::class,
                    'config' => [
                        'class' => Providers\Nolimit\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::MONEY_GO       => [
                    'class'  => Providers\MoneyGo\SendService::class,
                    'config' => [
                        'class' => Providers\MoneyGo\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::SD_TOP         => [
                    'class'  => Providers\SdTop\SendService::class,
                    'config' => [
                        'class' => Providers\SdTop\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::LEAD_LOAN      => [
                    'class'  => Providers\LeadLoan\SendService::class,
                    'config' => [
                        'class' => Providers\LeadLoan\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::AD_WORDS       => [
                    'class'  => Providers\AdWords\SendService::class,
                    'config' => [
                        'class' => Providers\AdWords\EnvironmentConfig::class,
                    ],
                ],
                LeadSource::CASHTAN_CREDIT => [
                    'class'  => Providers\CashtanCredit\SendService::class,
                    'config' => [
                        'class' => Providers\CashtanCredit\EnvironmentConfig::class,
                    ],
                ],
            ];
            $this->source  = $source;
            $this->event   = $event;
        }

        /**
         * @return \Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface|null
         * @var SendServiceInterface $sender
         */
        public function create()
        {
            $filtered = $this->filteredSenders();
            
            if (isset($filtered[$this->source])) {
                // Перевіряємо, чи існує подія в конфігурації для конфігураційних мереж
                $source_snake = Str::snake($this->source);
                $event_config = Config::get("cpa.events.{$this->event}.{$source_snake}");
                
                if (empty($event_config) || $event_config === null) {
                    return null;
                }
                
                $sender = app()->make($filtered[$this->source]['class'], $filtered[$this->source]['config'] ?? []);
                return $sender;
            }
            // Якщо немає специфічного сервісу, шукаємо мережу в БД і використовуємо універсальний сервіс
            $network = CpaNetwork::where('slug', $this->source)->where('is_active', true)->first();
            if ($network) {
                // Перевіряємо, чи існує подія в конфігурації мережі
                $config = $network->config ?? [];
                $event_config = $config['events'][$this->event] ?? [];
                
                // Якщо подія не існує або вимкнена, не створюємо сервіс
                if (empty($event_config) || (isset($event_config['enabled']) && $event_config['enabled'] === false)) {
                    return null;
                }
                
                return new UniversalSendService($network);
            }
            \Log::info("Trying to send conversion through disabled sender: $this->source");
            return null;
        }

        /**
         * @return array
         */
        private function filteredSenders()
        {
            $active_networks = $this->network_repository->all();
            $filtered = [];
            
            foreach ($active_networks as $slug => $network) {
                // Конвертуємо slug в camelCase для пошуку в senders
                $sender_key = Str::camel($slug);
                
                if (isset($this->senders[$sender_key])) {
                    // Зберігаємо по camelCase ключу для відповідності з $this->source
                    $filtered[$sender_key] = $this->senders[$sender_key];
                }
                // Тут можна додати логіку для динамічних сервісів у майбутньому
            }
            
            return $filtered;
        }

        /**
         * @param      $source
         * @param bool $default
         *
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
         *
         * @return bool
         */
        private function hasEvent($source, $event)
        {
            $source = Str::snake($source);
            return Config::has('cpa.events.' . $event . '.' . $source);
        }
    }