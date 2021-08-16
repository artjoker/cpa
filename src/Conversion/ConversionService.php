<?php

    namespace Artjoker\Cpa\Conversion;

    use Artjoker\Cpa\Interfaces\Conversion\ServiceInterface;
    use Artjoker\Cpa\Lead\LeadService;
    use Artjoker\Cpa\Models\Conversion;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Config;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Str;

    class ConversionService implements ServiceInterface
    {
        public $senders;
        /**
         * @var LeadService
         */
        protected $leadService;

        /**
         * ConversionService constructor.
         *
         * @param LeadService $leadService
         */
        public function __construct(LeadService $leadService)
        {
            $this->leadService = $leadService;
        }


        /**
         * Register conversion when goal is achieved
         * e.g. register 'sale' or 'register' event
         *
         * @param Model|int|string $leadModel     user who performs target action
         * @param string           $conversionId  internal conversion Id, for example orderId or userId
         * @param string           $event         registered event (declared in config)
         * @param array            $custom_params custom options for postback
         *
         * @return Conversion|null
         */
        public function register($leadModel, string $conversionId, string $event, array $custom_params = []): ?Conversion
        {
            if (empty($event) || !Config::has('cpa.events.' . $event)) {
                Log::debug("Trying to send conversion {$conversionId} with undefined 'event'", [static::class, $event]);
                return null;
            }
            $lead = $this->leadService->getLastLeadByUser($leadModel);
            if (is_null($lead)) {
                return null;
            }
            $source = $lead->source;
            $sender = (new SendServiceFactory($source, $event))->create();
            if (is_null($sender)) {
                return null;
            }

            $conversion = new Conversion();
            $conversion->lead()->associate($lead);
            $conversion->conversion_id = $conversionId;
            $conversion->event         = $event;

            if ($conversion->isExists()) {
                Log::info("Skipping sending duplicate conversion $conversionId", [static::class, $source]);
                return null;
            }

            /** @var Postback $result */
            $params                  = $this->getEventParams($event, $source);
            $params['custom_params'] = $custom_params;
            $result                  = $sender->send($conversion, $params);
            $conversion->request     = [
                'method' => $result->getRequest()->getMethod(),
                'uri'    => (string)$result->getRequest()->getUri(),
                'body'   => $result->getRequest()->getBody()->getContents(),
            ];

            $response = $result->getResponse();

            if ($response !== null) {

                $body = $response->getBody()->getContents();
                if (is_string($body) && !is_array(json_decode($body, true))) {
                    $body = ['result' => utf8_decode($body)];
                } elseif (is_string($body) && is_array(json_decode($body, true))) {
                    $body = json_decode($body, true);
                }

                $conversion->response = [
                    'code' => $response->getStatusCode(),
                    'body' => $body,
                ];
            } else {
                Log::error("Response for conversion $conversionId does not formed well.", [static::class, $response]);
            }
            $conversion->save();

            return $conversion;
        }

        private function getEventParams($event, $source, $default = []): array
        {
            $source = Str::snake($source);
            $params = Config::get('cpa.events.' . $event . '.' . $source, $default);

            return (array)$params;
        }
    }