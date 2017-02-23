<?php

namespace SteffenBrand\CurrCurr\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\SimpleCache\CacheInterface;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Mapper\ExchangeRatesMapper;
use SteffenBrand\CurrCurr\Mapper\MapperInterface;
use SteffenBrand\CurrCurr\Model\CacheConfig;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class EcbClient implements EcbClientInterface
{

    /**
     * @var string
     */
    private $exchangeRatesUrl;

    /**
     * @var CacheConfig
     */
    private $cacheConfig;

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
     * @param CacheConfig $cacheConfig
     * @param MapperInterface $mapper
     */
    public function __construct(string $exchangeRatesUrl = self::DEFAULT_EXCHANGE_RATES_URL,
                                CacheConfig $cacheConfig = null,
                                MapperInterface $mapper = null)
    {
        $this->exchangeRatesUrl = $exchangeRatesUrl;
        $this->cacheConfig = $cacheConfig;
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
            if (null !== $this->cacheConfig && $this->cacheConfig->getCache() instanceof CacheInterface) {
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
        $responseBody = $this->cacheConfig->getCache()->get($this->cacheConfig->getCacheKey(), null);
        if (null === $responseBody) {
            $response = $this->performRequest();
            if ($this->cacheConfig->getCacheTimeInSeconds() === CacheConfig::CACHE_UNTIL_MIDNIGHT) {
                $this->cacheConfig->setCacheTimeInSeconds(strtotime('tomorrow') - time());
            }
            $responseBody = $response->getBody()->getContents();
            $this->cacheConfig->getCache()->set($this->cacheConfig->getCacheKey(), $responseBody, $this->cacheConfig->getCacheTimeInSeconds());
        }

        $response = new Response(200, [], $responseBody);

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
