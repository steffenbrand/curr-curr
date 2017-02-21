<?php

namespace SteffenBrand\CurrCurr\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Mapper\ExchangeRatesMapper;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class EcbClient implements EcbClientInterface
{
    /**
     * @const string
     */
    const DEFAULT_EXCHANGE_RATES_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var string
     */
    private $exchangeRatesUrl;

    /**
     * @param string $exchangeRatesUrl
     * @param CacheInterface $cache
     */
    public function __construct(string $exchangeRatesUrl = self::DEFAULT_EXCHANGE_RATES_URL, CacheInterface $cache = null)
    {
        $this->exchangeRatesUrl = $exchangeRatesUrl;
        $this->cache = $cache;
        $this->client = new Client();
    }

    /**
     * @throws ExchangeRatesRequestFailedException
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array
    {
        try {
            if (null !== $this->cache) {
                $date = new \DateTime('now');
                $key = $date->format('YY-mm-dd');
                if (null === $responseBody = $this->cache->get($key)) {
                    $response = $this->performRequest();
                    $this->cache->clear();
                    $this->cache->set($key, $response->getBody()->getContents());
                } else {
                    $response = new Response(200, [], $responseBody);
                }
            } else {
                $response = $this->performRequest();
            }
        } catch (\Exception $e) {
            throw new ExchangeRatesRequestFailedException($e);
        }

        $mapper = new ExchangeRatesMapper();
        return $mapper->map($response);
    }

    /**
     * @return ResponseInterface
     */
    private function performRequest()
    {
        $response = $this->client->request('GET', $this->exchangeRatesUrl);
        return $response;
    }

}
