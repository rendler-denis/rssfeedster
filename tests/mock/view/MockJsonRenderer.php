<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 22/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Tests\Mock\View;

use KoderHut\RssFeedster\Classes\Contracts\IRenderer as Renderer;
use KoderHut\RssFeedster\Classes\Feedster;


/**
 * Class XmlRenderer
 * Build an RSS Atom valid feed xml file based on the data passed
 *
 * @package KoderHut\RssFeedster\Classes\View
 */
class MockJsonRenderer
    implements Renderer
{
    /**
     * Content type header
     */
    const RENDERER_CONTENT_TYPE = 'application/json';

    /**
     * Init method used to initialize the feed document
     *
     * @param array $channelData base channel data taken from settings
     */
    public function __construct($channelData = [])
    {
        $jsonDoc        = new \StdClass();
        $rssElement     = new \StdClass();
        $channelElement = new \StdClass();


        $this->addChannelData($channelElement, $channelData, $jsonDoc);

        $rssElement->channel = $channelElement;

        $jsonDoc->rss = $rssElement;

        $this->jsonDoc = $jsonDoc;
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
        $channel = $this->jsonDoc->rss->channel;

        if (empty($data)) {
            return json_encode($this->jsonDoc);
        }

        foreach ($data as $item) {
            $jsonItem = new \StdClass();

            $jsonItem->title       = $item->title;
            $jsonItem->link        = $item->url;
            $jsonItem->guid        = $item->url;
            $jsonItem->description = $item->description;
            $jsonItem->pubDate     = date('D, d-M-Y', $item->published_at);

            $channelItems[] = $jsonItem;
        }

        $channel->item = $channelItems;

        return json_encode($this->jsonDoc);
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

    /**
     * Add the channel data
     *
     * @param \DomDocument $xmlDoc
     * @param array        $channelData
     */
    protected function addChannelData($channelEl, $channelData, $jsonDoc)
    {
        $jsonDoc->generator = Feedster::FEED_GENERATOR_NAME;
        $jsonDoc->lastBuildDate = date('D, d-M-Y');

        foreach ($channelData as $element => $elValue) {
            $channelEl->$element = $elValue;
        }
    }
}
