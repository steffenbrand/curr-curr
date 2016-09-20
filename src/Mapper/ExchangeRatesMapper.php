<?php

namespace SteffenBrand\CurrCurr\Mapper;

use DateTime;
use Psr\Http\Message\ResponseInterface;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class ExchangeRatesMapper implements MapperInterface
{

    /**
     * @throws ExchangeRatesMappingFailedException
     * @param ResponseInterface $response
     * @return ExchangeRate[]
     */
    public function map(ResponseInterface $response): array
    {

        $exchangeRates = array();
        $body = $response->getBody()->getContents();

        // see if body contains anything
        if (empty($body) === false) {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($body);
            $errors = libxml_get_errors();

            // check if any errors occured during xml parsing
            if (empty($errors) === true) {
                $date = DateTime::createFromFormat('Y-m-d', $xml->Cube->Cube['time']);

                // check if any errors occured during date parsing
                if (false !== $date) {
                    $date->setTime(0, 0);
                    foreach ($xml->Cube->Cube->Cube as $item) {
                        $currency = strval($item['currency']);
                        $rate = floatval($item['rate']);
                        $exchangeRates[$currency] = new ExchangeRate(
                            $currency,
                            $rate,
                            $date
                        );
                    }

                    return $exchangeRates;
                }
            }
        }

        // throw exception if anything went wrong
        throw new ExchangeRatesMappingFailedException();
    }

}