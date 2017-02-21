<?php

namespace SteffenBrand\CurrCurr\Client;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;
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
     * @param CacheInterface $cache
     */
    public function __construct(string $expectedResponse, CacheInterface $cache = null)
    {
        switch ($expectedResponse) {
            case self::VALID_RESPONSE:
                $this->response = $this->createResponseFromFile(__DIR__ . '/../../resources/eurofxref-daily-valid.xml');
                break;
            case self::USD_MISSING_RESPONSE:
                $this->response = $this->createResponseFromFile(__DIR__ . '/../../resources/eurofxref-daily-usd-missing.xml');
                break;
            case self::DATE_MISSING_RESPONSE:
                $this->response = $this->createResponseFromFile(__DIR__ . '/../../resources/eurofxref-daily-date-missing.xml');
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

    /**
     * @param string $file
     * @return Response
     */
    private function createResponseFromFile(string $file): Response
    {
        return new Response(200, [], file_get_contents($file));
    }

}