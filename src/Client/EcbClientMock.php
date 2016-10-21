<?php

namespace SteffenBrand\CurrCurr\Client;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Mapper\ExchangeRatesMapper;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class EcbClientMock implements EcbClientInterface
{

    const VALID_RESPONSE = 'ValidResponse';
    const USD_MISSING_RESPONSE = 'UsdMissingResponse';
    const DATE_MISSING_RESPONSE = 'DateMissingResponse';

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
            case self::VALID_RESPONSE:
                $this->response = new Response(
                    200,
                    [],
                    file_get_contents(__DIR__ . '/../../resources/eurofxref-daily-valid.xml')
                );
                break;
            case self::USD_MISSING_RESPONSE:
                $this->response = new Response(
                    200,
                    [],
                    file_get_contents(__DIR__ . '/../../resources/eurofxref-daily-usd-missing.xml')
                );
                break;
            case self::DATE_MISSING_RESPONSE:
                $this->response = new Response(
                    200,
                    [],
                    file_get_contents(__DIR__ . '/../../resources/eurofxref-daily-date-missing.xml')
                );
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