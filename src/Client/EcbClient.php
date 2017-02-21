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
     * @const int
     */
    const CACHE_UNTIL_MIDNIGHT = -1;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var int
     */
    private $cacheTimeInSeconds;

    /**
     * @var string
     */
    private $exchangeRatesUrl;

    /**
     * @param string $exchangeRatesUrl
     * @param CacheInterface $cache
     * @param int $cacheTimeInSeconds -1 to cache until midnight
     */
    public function __construct(string $exchangeRatesUrl = self::DEFAULT_EXCHANGE_RATES_URL, CacheInterface $cache = null, int $cacheTimeInSeconds = self::CACHE_UNTIL_MIDNIGHT)
    {
        $this->exchangeRatesUrl = $exchangeRatesUrl;
        $this->cache = $cache;
        $this->cacheTimeInSeconds = $cacheTimeInSeconds;
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
                $now = new \DateTime();
                $key = 'curr-curr-' . $now->format('YY-mm-dd');
                if (null === $responseBody = $this->cache->get($key)) {
                    $response = $this->performRequest();
                    if ($this->cacheTimeInSeconds === self::CACHE_UNTIL_MIDNIGHT) {
                        $this->cacheTimeInSeconds = strtotime('tomorrow') - time();
                    }
                    $this->cache->set($key, $response->getBody()->getContents(), $this->cacheTimeInSeconds);
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
