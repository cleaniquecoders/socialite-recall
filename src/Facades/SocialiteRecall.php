<?php

namespace CleaniqueCoders\SocialiteRecall\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CleaniqueCoders\SocialiteRecall\SocialiteRecall
 */
class SocialiteRecall extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \CleaniqueCoders\SocialiteRecall\SocialiteRecall::class;
    }
}
