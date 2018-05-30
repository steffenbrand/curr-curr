<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr;

use SteffenBrand\CurrCurr\Client\EcbClient;
use SteffenBrand\CurrCurr\Client\EcbClientInterface;
use SteffenBrand\CurrCurr\Exception\CurrencyNotSupportedException;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Model\Currency;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

/**
 * Class CurrCurr
 * @package SteffenBrand\CurrCurr
 */
class CurrCurr
{
    /**
     * @var EcbClientInterface
     */
    private $client;

    /**
     * CurrCurr constructor.
     *
     * @param EcbClientInterface $client The ECB Client to use, leave blank for default ECB Client
     */
    public function __construct(EcbClientInterface $client = null)
    {
        if (null === $client) {
            $client = new EcbClient();
        }
        $this->client = $client;
    }

    /**
     * Delivers the current exchange rates based on EUR from the ECB.
     *
     * @throws ExchangeRatesMappingFailedException
     * @throws ExchangeRatesRequestFailedException
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array
    {
        return $this->client->getExchangeRates();
    }

    /**
     * Delivers the current exchange rates for a specific currency based on EUR from the ECB.
     *
     * @param string $currencyAbbr The currency to be requested. Use constants from Currency.
     * @return ExchangeRate
     * @throws ExchangeRatesMappingFailedException
     * @throws ExchangeRatesRequestFailedException
     * @throws CurrencyNotSupportedException
     */
    public function getExchangeRateByCurrency(string $currencyAbbr): ExchangeRate
    {
        $exchangeRates = $this->getExchangeRates();

        if (false === \array_key_exists($currencyAbbr, $exchangeRates) ||
            false === \in_array($currencyAbbr, Currency::ALLOWED_CURRENCIES, true))
        {
            throw new CurrencyNotSupportedException();
        }

        return $exchangeRates[$currencyAbbr];
    }
}