<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Model;

/**
 * Class Currency
 * @package SteffenBrand\CurrCurr\Model
 */
abstract class Currency
{
    public const USD = 'USD';
    public const JPY = 'JPY';
    public const BGN = 'BGN';
    public const CZK = 'CZK';
    public const DKK = 'DKK';
    public const GBP = 'GBP';
    public const HUF = 'HUF';
    public const PLN = 'PLN';
    public const RON = 'RON';
    public const SEK = 'SEK';
    public const CHF = 'CHF';
    public const NOK = 'NOK';
    public const HRK = 'HRK';
    public const RUB = 'RUB';
    public const TRY = 'TRY';
    public const AUD = 'AUD';
    public const BRL = 'BRL';
    public const CAD = 'CAD';
    public const CNY = 'CNY';
    public const HKD = 'HKD';
    public const IDR = 'IDR';
    public const ILS = 'ILS';
    public const INR = 'INR';
    public const KRW = 'KRW';
    public const MXN = 'MXN';
    public const MYR = 'MYR';
    public const NZD = 'NZD';
    public const PHP = 'PHP';
    public const SGD = 'SGD';
    public const THB = 'THB';
    public const ZAR = 'ZAR';

    public const ALLOWED_CURRENCIES = [
        self::USD,
        self::JPY,
        self::BGN,
        self::CZK,
        self::DKK,
        self::GBP,
        self::HUF,
        self::PLN,
        self::RON,
        self::SEK,
        self::CHF,
        self::NOK,
        self::HRK,
        self::RUB,
        self::TRY,
        self::AUD,
        self::BRL,
        self::CAD,
        self::CNY,
        self::HKD,
        self::IDR,
        self::ILS,
        self::INR,
        self::KRW,
        self::MXN,
        self::MYR,
        self::NZD,
        self::PHP,
        self::SGD,
        self::THB,
        self::ZAR
    ];
}