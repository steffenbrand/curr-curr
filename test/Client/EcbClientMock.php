<?php

namespace SteffenBrand\CurrCurr\Test\Client;

use Psr\Http\Message\ResponseInterface;
use SteffenBrand\CurrCurr\Client\EcbClientInterface;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Mapper\ExchangeRatesMapper;
use SteffenBrand\CurrCurr\Model\ExchangeRate;
use SteffenBrand\CurrCurr\Test\Response\DateMissingResponse;
use SteffenBrand\CurrCurr\Test\Response\UsdMissingResponse;
use SteffenBrand\CurrCurr\Test\Response\ValidResponse;

class EcbClientMock implements EcbClientInterface
{

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param string $exchangeRatesUrl
     */
    public function __construct(string $exchangeRatesUrl = self::DEFAULT_EXCHANGE_RATES_URL)
    {
        switch ($exchangeRatesUrl) {
            case 'ValidResponse':
                $this->response = new ValidResponse();
                break;
            case 'UsdMissingResponse':
                $this->response = new UsdMissingResponse();
                break;
            case 'DateMissingResponse':
                $this->response = new DateMissingResponse();
                break;
        }
    }

    /**
     * @throws ExchangeRatesRequestFailedException
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array
    {
        $mapper = new ExchangeRatesMapper();
        return $mapper->map($this->response);
    }

}