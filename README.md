# CurrCurr - Current Currency (Rates)
![alt tag](https://travis-ci.org/steffenbrand/curr-curr.svg?branch=master)
![alt tag](https://codecov.io/github/steffenbrand/curr-curr/coverage.svg)
[![Latest Stable Version](https://poser.pugx.org/steffenbrand/curr-curr/version)](https://packagist.org/packages/steffenbrand/curr-curr)
[![Latest Unstable Version](https://poser.pugx.org/steffenbrand/curr-curr/v/unstable)](//packagist.org/packages/steffenbrand/curr-curr)
[![Total Downloads](https://poser.pugx.org/steffenbrand/curr-curr/downloads)](https://packagist.org/packages/steffenbrand/curr-curr)
[![License](https://poser.pugx.org/steffenbrand/curr-curr/license)](https://packagist.org/packages/steffenbrand/curr-curr)
[![composer.lock available](https://poser.pugx.org/steffenbrand/curr-curr/composerlock)](https://packagist.org/packages/steffenbrand/curr-curr)

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
