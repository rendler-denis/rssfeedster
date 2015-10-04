<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Classes\View;

use KoderHut\RssFeedster\Classes\Contracts\IRenderer as Renderer;
use KoderHut\RssFeedster\Classes\Feedster;


/**
 * Class XmlRenderer
 * Build an RSS Atom valid feed xml file based on the data passed
 *
 * @package KoderHut\RssFeedster\Classes\View
 */
class XmlRenderer
    implements Renderer
{
    /**
     * Content type header
     */
    const RENDERER_CONTENT_TYPE = 'application/atom+xml';

    /**
     * Sections that need to be wrapped into a CDATA element
     *
     * @var array
     */
    private $cdataSections = ['link', 'description', ];

    /**
     * Init method used to initialize the feed document
     *
     * @param array $channelData base channel data taken from settings
     */
    public function __construct($channelData = [])
    {
        $xmlDoc         = new \DOMDocument('1.0', 'UTF-8');
        $rssElement     = $xmlDoc->createElement('rss');
        $channelElement = $xmlDoc->createElement('channel');

        $xmlDoc->formatOutput = true;

        $rssElement->setAttribute('version', '2.0');
        $rssElement->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');

        $this->addChannelData($channelElement, $channelData, $xmlDoc);

        $rssElement->appendChild($channelElement);

        $xmlDoc->appendChild($rssElement);

        $this->xml = $xmlDoc;
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
        $channel = $this->xml->getElementsByTagName('channel')->item(0);

        if (empty($data)) {
            return $this->xml->saveXML();
        }

        foreach ($data as $item) {
            $xmlItem = $this->xml->createElement('item');

            $xmlItem->appendChild(
                $this->xml->createElement('title', $item->title)
            );

            $link = $this->xml->createElement('link');
            $link->appendChild(
                $this->xml->createCDATASection($item->url)
            );
            $xmlItem->appendChild($link);

            $guid = $this->xml->createElement('guid');
            $guid->appendChild(
                $this->xml->createCDATASection($item->url)
            );
            $xmlItem->appendChild($guid);

            $description = $this->xml->createElement('description');
            $description->appendChild(
                $this->xml->createCDATASection($item->summary)
            );
            $xmlItem->appendChild($description);

            $xmlItem->appendChild(
                $this->xml->createElement('pubDate', date('D, d-M-Y', $item->published_at))
            );

            $channel->appendChild($xmlItem);

            unset($link, $description, $guid, $xmlItem);
        }

        return $this->xml->saveXML();
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
    protected function addChannelData($channelEl, $channelData, $xmlDoc)
    {
        $atomLink  = $xmlDoc->createElement('atom:link');
        $generator = $xmlDoc->createElement('generator', Feedster::FEED_GENERATOR_NAME);
        $buildDate = $xmlDoc->createElement('lastBuildDate', date('D, d-M-Y'));

        foreach ($channelData as $element => $elValue) {
            if (in_array($element, $this->cdataSections)) {
                $elValue = $xmlDoc->createCDATASection($elValue);
            }
            else {
                $elValue = $xmlDoc->createTextNode($elValue);
            }

            $child = $xmlDoc->createElement($element);
            $child->appendChild($elValue);

            $channelEl->appendChild($child);
        }

        $atomLink->setAttribute('rel', 'self');
        $atomLink->setAttribute('type', self::RENDERER_CONTENT_TYPE);
        $atomLink->setAttribute('href', isset($channelData['link']) ? $channelData['link']: '');

        $channelEl->appendChild($atomLink);
        $channelEl->appendChild($generator);
        $channelEl->appendChild($buildDate);
    }
}
