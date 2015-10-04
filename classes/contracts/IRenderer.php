<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Classes\Contracts;

/**
 * Interface IRenderer
 *
 * Renderer interface
 *
 * @package KoderHut\RssFeedster\Classes\Interfaces
 */
interface IRenderer
{
    /**
     * Render the data
     *
     * @param $data
     *
     * @return mixed
     */
    public function renderData($data);

    /**
     * Return the content-type for this renderer
     *
     * @return string
     */
    public function getContentType();
}