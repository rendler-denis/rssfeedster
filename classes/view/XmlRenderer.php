<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Classes\View;

use KoderHut\RssFeedster\Classes\Contracts\IAdapter;
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
    const RENDERER_CONTENT_TYPE = 'text/xml';

    /**
     * Sections that need to be wrapped into a CDATA element
     *
     * @var array
     */
    private $cdataSections = ['link', 'description', ];

    /**
     * Init method used to initialize the feed document
     *
     * @param array   $channelData base channel data taken from settings
     * @param string  $xmlTemplate
     * @param IAdapter $adapter
     */
    public function __construct($channelData = [], $xmlTemplate = null, IAdapter $adapter = null)
    {
        $this->adapter = $adapter;

        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');
        $xmlDoc->formatOutput = true;

        if (null !== $xmlTemplate) {
            $xmlDoc->loadXML($xmlTemplate);
        }

        $this->itemTmpl = $xmlDoc->getElementsByTagName('item')->item(0);

        if (null !== $this->itemTmpl) {
            $channelElement = $xmlDoc->getElementsByTagName('channel')->item(0);
            $channelElement->removeChild($this->itemTmpl);
        }

        $this->addChannelData($channelData, $xmlDoc);

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
        $channel  = $this->xml->getElementsByTagName('channel')->item(0);

        if (0 == count($data) || !$this->itemTmpl->hasChildNodes()) {
            return $this->xml->saveXML();
        }

        foreach ($data as $dataItem) {
            $xmlItem = clone($this->itemTmpl);

            foreach ($xmlItem->childNodes as $node) {
                if (!$node instanceof \DOMElement) {
                    continue;
                }

                $this->adapter->getDataValue($node, $dataItem);
            }

            $channel->appendChild($xmlItem);
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
    protected function addChannelData($channelData, $xmlDoc)
    {
        $xmlDoc->getElementsByTagName('generator')->item(0)
            ->textContent = Feedster::FEED_GENERATOR_NAME;
        $xmlDoc->getElementsByTagName('lastBuildDate')->item(0)
            ->textContent = date('r');

        foreach ($channelData as $element => $elValue) {
            $xmlItem = $xmlDoc->getElementsByTagName($element)->item(0);

            if ($xmlItem instanceof \DOMElement) {
                $xmlItem->textContent = $elValue;
            }
        }

        $atomLink = $xmlDoc->getElementsByTagNameNS($xmlDoc->lookupNamespaceUri('atom'), 'link')->item(0);
        if ($atomLink instanceof \DOMElement) {
            $atomLink->setAttribute('href', isset($channelData['link']) ? $channelData['link']: '');
        }
    }
}