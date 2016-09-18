<?php

namespace SteffenBrand\CurrCurr\Mapper;

use Psr\Http\Message\ResponseInterface;

interface MapperInterface
{

    public function map(ResponseInterface $response): array;

}