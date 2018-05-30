<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Model;

use Psr\SimpleCache\CacheInterface;

/**
 * Class CacheConfig
 * @package SteffenBrand\CurrCurr\Model
 */
class CacheConfig
{
    /**
     * @const int
     */
    public const CACHE_UNTIL_MIDNIGHT = -1;

    /**
     * @const string
     */
    public const DEFAULT_CACHE_KEY = 'curr-curr-cache';

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var int
     */
    private $cacheTimeInSeconds;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * CacheConfig constructor.
     *
     * @param CacheInterface $cache PSR-16 compliant CacheInterface implementation
     * @param int $cacheTimeInSeconds TTL in seconds
     * @param string $cacheKey Key to use for caching
     */
    public function __construct(
        CacheInterface $cache = null,
        int $cacheTimeInSeconds = self::CACHE_UNTIL_MIDNIGHT,
        string $cacheKey = self::DEFAULT_CACHE_KEY)
    {
        $this->cache = $cache;
        $this->cacheTimeInSeconds = $cacheTimeInSeconds;
        $this->cacheKey = $cacheKey;
    }

    /**
     * @return CacheInterface
     */
    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return int
     */
    public function getCacheTimeInSeconds(): int
    {
        return $this->cacheTimeInSeconds;
    }

    /**
     * @param int $cacheTimeInSeconds
     */
    public function setCacheTimeInSeconds(int $cacheTimeInSeconds)
    {
        $this->cacheTimeInSeconds = $cacheTimeInSeconds;
    }

    /**
     * @return string
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * @param string $cacheKey
     */
    public function setCacheKey(string $cacheKey)
    {
        $this->cacheKey = $cacheKey;
    }
}