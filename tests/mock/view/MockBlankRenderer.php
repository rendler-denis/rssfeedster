<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 22/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Tests\Mock\View;

use KoderHut\RssFeedster\Classes\Contracts\IRenderer as Renderer;


/**
 * Class MockBlankRenderer
 * Build an RSS Atom valid feed xml file based on the data passed
 *
 * @package KoderHut\RssFeedster\Classes\View
 */
class MockBlankRenderer
    implements Renderer
{
    /**
     * Content type header
     */
    const RENDERER_CONTENT_TYPE = 'text/html';

    /**
     * Init method used to initialize the feed document
     *
     * @param array $channelData base channel data taken from settings
     */
    public function __construct($channelData = [])
    {
       $this->data['channel'] = $channelData;
    }

    /**
     * Build the feed items and attach them to the feed document
     *
     * @param array|Illuminate\Contracts\Pagination\Paginator $data
     *
     * @return mixed
     */
    public function renderData($data = [])
    {
        if (empty($data)) {
            return $this->data;
        }

        $this->data['items'] = $data;

        return $this->data;
    }

    /**
     * Return the content-type for this renderer
     *
     * @return string
     */
    public function getContentType()
    {
        return self::RENDERER_CONTENT_TYPE;
    }
}
