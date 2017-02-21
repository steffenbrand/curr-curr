<?php

namespace SteffenBrand\CurrCurr\Model;

class ExchangeRate
{

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var float
     */
    private $rate;

    /**
     * ExchangeRate constructor.
     * @param string $currency
     * @param float $rate
     * @param \DateTime $date
     */
    public function __construct(string $currency, float $rate, \DateTime $date)
    {
        $this->currency = $currency;
        $this->rate = $rate;
        $this->date = $date;
    }

    /**
     * @return string The abbreviation of the currency
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return float The exchange rate based on EUR
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @return \DateTime The date on which this exchange rate is valid
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

}