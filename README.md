# CurrCurr - Current Currency (Exchange Rates)
[![Build](https://travis-ci.org/steffenbrand/curr-curr.svg?branch=master)](https://travis-ci.org/steffenbrand/curr-curr)
[![Coverage](https://codecov.io/github/steffenbrand/curr-curr/coverage.svg)](https://codecov.io/gh/steffenbrand/curr-curr)
[![Latest Stable Version](https://poser.pugx.org/steffenbrand/curr-curr/version)](https://packagist.org/packages/steffenbrand/curr-curr)
[![Latest Unstable Version](https://poser.pugx.org/steffenbrand/curr-curr/v/unstable)](//packagist.org/packages/steffenbrand/curr-curr)
[![Total Downloads](https://poser.pugx.org/steffenbrand/curr-curr/downloads)](https://packagist.org/packages/steffenbrand/curr-curr)
[![License](https://poser.pugx.org/steffenbrand/curr-curr/license)](https://packagist.org/packages/steffenbrand/curr-curr)
[![composer.lock available](https://poser.pugx.org/steffenbrand/curr-curr/composerlock)](https://packagist.org/packages/steffenbrand/curr-curr)

![CurrCurr Logo](https://github.com/steffenbrand/curr-curr/blob/master/curr-curr.jpg)

Delivers current exchange rates for EUR provided by the ECB under https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml as PHP objects.

* [CurrCurr on Packagist](https://packagist.org/packages/steffenbrand/curr-curr)
* [CurrCurr on GitHub](https://github.com/steffenbrand/curr-curr)

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
    // webservice might not be present
} catch (ExchangeRatesMappingFailedException $e) {
    // webservice might not deliver what we expect
} catch (CurrencyNotSupportedException $e) {
    // requested currency might not be provided
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
    // webservice might not be present
} catch (ExchangeRatesMappingFailedException $e) {
    // webservice might not deliver what we expect
}
```
