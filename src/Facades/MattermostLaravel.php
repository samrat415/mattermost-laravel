<?php

namespace Samrat415\MattermostLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Samrat415\MattermostLaravel\MattermostLaravel
 */
class MattermostLaravel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Samrat415\MattermostLaravel\MattermostLaravel::class;
    }
}
