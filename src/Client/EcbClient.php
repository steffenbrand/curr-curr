<?php

namespace SteffenBrand\CurrCurr\Client;

use Exception;
use GuzzleHttp\Client;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Mapper\ExchangeRatesMapper;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class EcbClient implements EcbClientInterface
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $exchangeRatesUrl;

    /**
     * @param string $exchangeRatesUrl
     */
    public function __construct(string $exchangeRatesUrl = self::DEFAULT_EXCHANGE_RATES_URL)
    {
        $this->exchangeRatesUrl = $exchangeRatesUrl;
        $this->client = new Client();
    }

    /**
     * @throws ExchangeRatesRequestFailedException
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array
    {
        try {
            $response = $this->client->request(self::HTTP_GET, $this->exchangeRatesUrl);
        } catch (Exception $e) {
            throw new ExchangeRatesRequestFailedException($e);
        }

        $mapper = new ExchangeRatesMapper();
        return $mapper->map($response);
    }

}