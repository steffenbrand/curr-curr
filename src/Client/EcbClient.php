<?php

namespace SteffenBrand\CurrCurr\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\SimpleCache\CacheInterface;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Mapper\ExchangeRatesMapper;
use SteffenBrand\CurrCurr\Mapper\MapperInterface;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class EcbClient implements EcbClientInterface
{

    /**
     * @var string
     */
    private $exchangeRatesUrl;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var int
     */
    private $cacheTimeInSeconds;

    /**
     * @var MapperInterface
     */
    private $mapper;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param string $exchangeRatesUrl
     * @param CacheInterface $cache
     * @param int $cacheTimeInSeconds
     */
    public function __construct(string $exchangeRatesUrl = self::DEFAULT_EXCHANGE_RATES_URL,
                                CacheInterface $cache = null,
                                int $cacheTimeInSeconds = self::CACHE_UNTIL_MIDNIGHT,
                                MapperInterface $mapper = null)
    {
        $this->exchangeRatesUrl = $exchangeRatesUrl;
        $this->cache = $cache;
        $this->cacheTimeInSeconds = $cacheTimeInSeconds;
        if (null === $mapper) {
            $mapper = new ExchangeRatesMapper();
        }
        $this->mapper = $mapper;
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
                $response = $this->performCachedRequest();
            } else {
                $response = $this->performRequest();
            }
        } catch (\Exception $e) {
            throw new ExchangeRatesRequestFailedException($e);
        }

        return $this->mapper->map($response);
    }

    /**
     * @return Response
     */
    private function performCachedRequest()
    {
        $now = new \DateTime();
        $key = 'curr-curr-' . $now->format('YY-mm-dd');
        $responseBody = $this->cache->get($key);

        if (null === $responseBody) {
            $response = $this->performRequest();
            if ($this->cacheTimeInSeconds === self::CACHE_UNTIL_MIDNIGHT) {
                $this->cacheTimeInSeconds = strtotime('tomorrow') - time();
            }
            $this->cache->set($key, $response->getBody()->getContents(), $this->cacheTimeInSeconds);
        } else {
            $response = new Response(200, [], $responseBody);
        }

        return $response;
    }

    /**
     * @return Response
     */
    private function performRequest()
    {
        $response = $this->client->request('GET', $this->exchangeRatesUrl);
        return $response;
    }

}
