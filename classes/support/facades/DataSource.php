<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Classes\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DataSource
 * Facade for the data source object
 *
 * @package KoderHut\RssFeedster\Classes\Support\Facades
 */
class DataSource
    extends Facade
{
    /**
     * Return the facade namespace
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'KoderHut\RssFeedster\DataSource';
    }
}