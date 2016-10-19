<?php

namespace SteffenBrand\CurrCurr\Client;

use Psr\Http\Message\ResponseInterface;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Mapper\ExchangeRatesMapper;
use SteffenBrand\CurrCurr\Model\ExchangeRate;
use SteffenBrand\CurrCurr\Response\DateMissingResponse;
use SteffenBrand\CurrCurr\Response\UsdMissingResponse;
use SteffenBrand\CurrCurr\Response\ValidResponse;

class EcbClientMock implements EcbClientInterface
{

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param string $expectedResponse
     */
    public function __construct(string $expectedResponse)
    {
        switch ($expectedResponse) {
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