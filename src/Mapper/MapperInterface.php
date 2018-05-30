<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Mapper;

use Psr\Http\Message\ResponseInterface;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

interface MapperInterface
{
    /**
     * @param ResponseInterface $response
     * @return ExchangeRate[]
     */
    public function map(ResponseInterface $response): array;
}