<?php

namespace SteffenBrand\CurrCurr\Test\Client;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use SteffenBrand\CurrCurr\Client\EcbClientInterface;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Mapper\ExchangeRatesMapper;
use SteffenBrand\CurrCurr\Mapper\MapperInterface;
use SteffenBrand\CurrCurr\Model\CacheConfig;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class EcbClientMock implements EcbClientInterface
{

    /**
     * @const string
     */
    const VALID_RESPONSE = 'ValidResponse';

    /**
     * @const string
     */
    const USD_MISSING_RESPONSE = 'UsdMissingResponse';

    /**
     * @const string
     */
    const DATE_MISSING_RESPONSE = 'DateMissingResponse';

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var MapperInterface
     */
    private $mapper;

    /**
     * @param string $expectedResponse
     * @param CacheConfig $cacheConfig
     * @param MapperInterface $mapper
     */
    public function __construct(string $expectedResponse = self::VALID_RESPONSE,
                                CacheConfig $cacheConfig = null,
                                MapperInterface $mapper = null)
    {
        if (null === $mapper) {
            $mapper = new ExchangeRatesMapper();
        }
        $this->mapper = $mapper;

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
        return $this->mapper->map($this->response);
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