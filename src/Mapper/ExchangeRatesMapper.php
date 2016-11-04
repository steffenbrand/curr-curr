<?php

namespace SteffenBrand\CurrCurr\Mapper;

use DateTime;
use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class ExchangeRatesMapper implements MapperInterface
{

    /**
     * @param ResponseInterface $response
     * @return ExchangeRate[]
     */
    public function map(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();
        $xml = $this->parseBody($body);
        $date = $this->parseDate($xml);
        $exchangeRates = $this->parseExchangeRates($xml, $date);

        return $exchangeRates;
    }

    /**
     * @param string $body
     * @return SimpleXMLElement
     */
    private function parseBody(string $body): SimpleXMLElement
    {
        if (empty($body) === false) {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($body);
            $errors = libxml_get_errors();
            if (empty($errors) === true) {
                return $xml;
            }
        }

        throw new ExchangeRatesMappingFailedException();
    }

    /**
     * @param SimpleXMLElement $xml
     * @return DateTime
     */
    private function parseDate(SimpleXMLElement $xml): DateTime
    {
        $date = DateTime::createFromFormat('Y-m-d', $xml->Cube->Cube['time']);
        if (false !== $date) {
            $date->setTime(0, 0);
            return $date;
        }

        throw new ExchangeRatesMappingFailedException();
    }

    /**
     * @param SimpleXMLElement $xml
     * @param DateTime $date
     * @return array
     */
    private function parseExchangeRates(SimpleXMLElement $xml, DateTime $date): array
    {
        $exchangeRates = [];

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