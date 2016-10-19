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
                $this->response = new Response(
                    200,
                    [],
                    $this->createStreamFromXmlFile(__DIR__ . '/../../resources/eurofxref-daily-valid.xml')
                );
                break;
            case 'UsdMissingResponse':
                $this->response = new Response(
                    200,
                    [],
                    $this->createStreamFromXmlFile(__DIR__ . '/../../resources/eurofxref-daily-usd-missing.xml')
                );
                break;
            case 'DateMissingResponse':
                $this->response = new Response(
                    200,
                    [],
                    $this->createStreamFromXmlFile(__DIR__ . '/../../resources/eurofxref-daily-date-missing.xml')
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

    /**
     * @param string $file
     * @return Stream
     */
    private function createStreamFromXmlFile(string $file): Stream
    {
        return new Stream(
            fopen(
                'data://application/xml,' . file_get_contents($file),
                'r'
            )
        );
    }

}