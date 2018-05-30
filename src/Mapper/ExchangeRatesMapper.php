<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Mapper;

use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

/**
 * Class ExchangeRatesMapper
 * @package SteffenBrand\CurrCurr\Mapper
 */
class ExchangeRatesMapper implements MapperInterface
{
    /**
     * Map exchange rates response.
     *
     * @param ResponseInterface $response
     * @return ExchangeRate[]
     * @throws \SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException
     */
    public function map(ResponseInterface $response): array
    {
        $body = (string) $response->getBody();
        $xml = $this->parseBody($body);
        $date = $this->parseDate($xml);

        return $this->parseExchangeRates($xml, $date);
    }

    /**
     * Parse body.
     *
     * @param string $body
     * @return SimpleXMLElement
     * @throws \SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException
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
     * Parse date.
     *
     * @param SimpleXMLElement $xml
     * @return \DateTime
     * @throws \SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException
     */
    private function parseDate(SimpleXMLElement $xml): \DateTime
    {
        $date = \DateTime::createFromFormat('Y-m-d', (string) $xml->Cube->Cube['time']);
        if (false !== $date) {
            $date->setTime(0, 0);
            return $date;
        }

        throw new ExchangeRatesMappingFailedException();
    }

    /**
     * Parse exchange rates.
     *
     * @param SimpleXMLElement $xml
     * @param \DateTime $date
     * @return array
     */
    private function parseExchangeRates(SimpleXMLElement $xml, \DateTime $date): array
    {
        $exchangeRates = [];

        foreach ($xml->Cube->Cube->Cube as $item) {
            $currency = (string) $item['currency'];
            $rate = (float) $item['rate'];
            $exchangeRates[$currency] = new ExchangeRate(
                $currency,
                $rate,
                $date
            );
        }

        return $exchangeRates;
    }
}