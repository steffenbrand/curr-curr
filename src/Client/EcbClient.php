<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Mapper\ExchangeRatesMapper;
use SteffenBrand\CurrCurr\Mapper\MapperInterface;
use SteffenBrand\CurrCurr\Model\CacheConfig;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

/**
 * Class EcbClient
 * @package SteffenBrand\CurrCurr\Client
 */
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
     * EcbClient constructor.
     *
     * @param string $exchangeRatesUrl
     * @param CacheConfig|null $cacheConfig
     * @param MapperInterface|null $mapper
     */
    public function __construct(
        string $exchangeRatesUrl = self::DEFAULT_EXCHANGE_RATES_URL,
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
     * Get exchange rates.
     *
     * @return ExchangeRate[]
     * @throws \SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
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
     * Perform cached request.
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function performCachedRequest(): ResponseInterface
    {
        $responseBody = $this->cacheConfig->getCache()->get($this->cacheConfig->getCacheKey(), null);
        if (null !== $responseBody) {
            return new Response(200, [], $responseBody);
        }

        $response = $this->performRequest();

        if ($this->cacheConfig->getCacheTimeInSeconds() === CacheConfig::CACHE_UNTIL_MIDNIGHT) {
            $this->cacheConfig->setCacheTimeInSeconds(strtotime('tomorrow') - time());
        }

        $responseBody = (string) $response->getBody();

        $this->cacheConfig->getCache()->set(
            $this->cacheConfig->getCacheKey(),
            $responseBody,
            $this->cacheConfig->getCacheTimeInSeconds()
        );

        return $response;
    }

    /**
     * Perform request.
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function performRequest(): ResponseInterface
    {
        return $this->client->request('GET', $this->exchangeRatesUrl);
    }

}
