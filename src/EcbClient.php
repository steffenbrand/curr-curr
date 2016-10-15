<?php

namespace SteffenBrand\CurrCurr;

use Exception;
use GuzzleHttp\Client;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Mapper\ExchangeRatesMapper;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class EcbClient
{

    /**
     * @const string
     */
    const HTTP_GET = 'GET';

    /**
     * @const string
     */
    const DEFAULT_EXCHANGE_RATES_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

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
    public function __construct(string $exchangeRatesUrl)
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