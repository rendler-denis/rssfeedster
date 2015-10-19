<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 13/10/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Classes\Contracts;

/**
 * Interface IAdapter
 *
 * @package KoderHut\RssFeedster\Classes\Contracts
 */
interface IAdapter
{
    /**
     * Namespace const used for DI
     */
    const DI_NAMESPACE = 'KoderHut\RssFeedster\Adapter';

    /**
     * Method used to connect a data source to a renderer
     *
     * @param mixed $item
     * @param mixed $dataItem
     *
     * @return mixed
     */
    public function getDataValue($item, $dataItem);
}