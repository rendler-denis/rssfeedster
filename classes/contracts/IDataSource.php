<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Classes\Contracts;

/**
 * Interface IDataSource
 *
 * Data source interface
 *
 * @package KoderHut\RssFeedster\Classes\Contracts
 */
interface IDataSource
{
    /**
     * Load the data from source
     *
     * @return mixed
     */
    public function loadData();

    /**
     * Load, if not laready loaded, and return the data
     *
     * @return mixed
     */
    public function getData();
}