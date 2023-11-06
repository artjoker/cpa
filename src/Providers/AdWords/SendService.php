<?php

    namespace Artjoker\Cpa\Providers\AdWords;

    use Artjoker\Cpa\Conversion\Postback;
    use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
    use Artjoker\Cpa\Interfaces\Lead\LeadSource;
    use Artjoker\Cpa\Models\Conversion;
    use Artjoker\Cpa\Traits\SendServiceTrait;
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;
    use GuzzleHttp\Handler\MockHandler;
    use GuzzleHttp\HandlerStack;
    use GuzzleHttp\Psr7\Request;
    use GuzzleHttp\Psr7\Response;
    use Mockery\Mock;

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
            $this->source = LeadSource::AD_WORDS;
        }

        /**
         * @param Conversion $conversion
         * @param array      $params
         *
         * @return Postback
         */
        final public function send(Conversion $conversion, array $params = []): Postback
        {
            // Create a mock and queue two responses.
            $mock = new MockHandler([
                new Response(200, ['X-Foo' => 'Bar'], 'AdWords'),
                new Response(202, ['Content-Length' => 0])
            ]);

            $handlerStack = HandlerStack::create($mock);
            $client = new Client(['handler' => $handlerStack]);

            return new Postback($this->getRequest($conversion, $params), $client->request('GET', '/'));
        }

        /**
         * @param Conversion $conversion
         * @param array      $params
         *
         * @return Request
         */
        protected function getRequest(Conversion $conversion, array $params): Request
        {
            return new Request('GET', 'AdWords');
        }
    }
