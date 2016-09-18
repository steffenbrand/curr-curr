# CurrCurr - Current Currency (Rates)
![alt tag](https://travis-ci.org/steffenbrand/ezb-exchange-rates.svg?branch=master)

Delivers current exchange rates for EUR provided by the ECB under https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml as PHP objects.

## How to install

```
composer require steffenbrand/curr-curr
```

## How to use

### Request exchange rate for specific currency

```php
try {
    $cc = new CurrCurr();
    $exchangeRate = $cc->getExchangeRateByCurrency(Currency::USD);

    $exchangeRate->getDate();
    $exchangeRate->getCurrency();
    $exchangeRate->getRate();
} catch (ExchangeRatesRequestFailedException $e) {
    // this might fail, since we rely on a webservice
} catch (ExchangeRatesMappingFailedException $e) {
    // this might fail, since the webservice might change
} catch (CurrencyNotSupportedException $e) {
    // you might request an exchange rate, that is not provided
}
```

### Request all available exchange rates

```php
try {
    $cc = new CurrCurr();
    $exchangeRates = $cc->getExchangeRates();

    $exchangeRates[Currency::USD]->getDate();
    $exchangeRates[Currency::USD]->getCurrency();
    $exchangeRates[Currency::USD]->getRate();

    foreach ($exchangeRates as $exchangeRate) {
        $exchangeRate->getDate();
        $exchangeRate->getCurrency();
        $exchangeRate->getRate();
    }
} catch (ExchangeRatesRequestFailedException $e) {
    // this might fail, since we rely on a webservice
} catch (ExchangeRatesMappingFailedException $e) {
    // this might fail, since the webservice might change
}
```
