<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Tests\Classes\View;

use PluginTestCase,
    Model;

use KoderHut\RssFeedster\Classes\View\XmlRenderer;


/**
 * Class XmlRendererTest
 * @package KoderHut\RssFeedster\Tests\Classes\View
 */
class XmlRendererTest
    extends PluginTestCase
{
    /**
     * Base feed XML structure
     *
     * @var string
     */
    private $xmlBaseStructure = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <description />
    <title />
    <category />
    <copyright />
    <language />
    <link />
    <atom:link rel="self" type="application/atom+xml" href=""/>
    <generator />
    <lastBuildDate />
  </channel>
</rss>
XML;

    /**
     * Base feed XML structure for the channel tag
     *
     * @var string
     */
    private $xmlChannelStructure = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <item>
      <title>post title</title>
      <link><![CDATA[post url]]></link>
      <guid><![CDATA[post url]]></guid>
      <description><![CDATA[post summary]]></description>
      <pubDate>Tue, 29-Sep-2015</pubDate>
    </item>

    <item>
      <title>post title</title>
      <link><![CDATA[post url]]></link>
      <guid><![CDATA[post url]]></guid>
      <description><![CDATA[post summary]]></description>
      <pubDate>Tue, 29-Sep-2015</pubDate>
    </item>

    <item>
      <title>post title</title>
      <link><![CDATA[post url]]></link>
      <guid><![CDATA[post url]]></guid>
      <description><![CDATA[post summary]]></description>
      <pubDate>Tue, 29-Sep-2015</pubDate>
    </item>
  </channel>
</rss>
XML;

    /**
     * Object placeholder
     *
     * @var null
     */
    private $xml = null;

    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->xml = new XmlRenderer([
            'description' => 'feed description',
            'title'       => 'feed title',
            'category'    => 'feed category',
            'copyright'   => 'feed copyright',
            'language'    => 'en_gb',
            'link'        => 'http://koderhut.eu'
        ]);
    }

    /**
     * Tear down after test
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->xml);
    }

    /**
     * Test the XMLRenderer initial object
     */
    public function testObjectBuild()
    {
        $baseXmlDoc = new \DOMDocument();
        $objXmlDoc  = new \DOMDocument();

        $objXmlDoc->loadXml($this->xml->renderData());
        $baseXmlDoc->loadXml($this->xmlBaseStructure);

        $this->assertEquals(XmlRenderer::RENDERER_CONTENT_TYPE, $this->xml->getContentType());

        $this->assertEqualXMLStructure($baseXmlDoc->firstChild, $objXmlDoc->firstChild);

        $this->assertEqualXMLStructure($baseXmlDoc->firstChild, $objXmlDoc->firstChild, true);
    }

    /**
     * Test the XMLRenderer object with data
     */
    public function testItemRender()
    {
        $objXmlDoc   = new \DOMDocument();
        $baseRssDoc  = new \DOMDocument();
        $objItem     = false;
        $baseItem    = false;
        $attributes = [
            'title'   => 'post title',
            'url'     => 'post url',
            'summary' => 'post summary',
            'pubDate' => 'Tue, 29-Sep-2015',
        ];
        $items      = [
            Model::make($attributes),
            Model::make($attributes),
            Model::make($attributes),
        ];

        $objXmlDoc->loadXml($this->xml->renderData($items));
        $baseRssDoc->loadXml($this->xmlChannelStructure);

        $this->assertEqualXMLStructure(
            $baseRssDoc->getElementsByTagName('item')->item(0),
            $objXmlDoc->getElementsByTagName('item')->item(0)
        );

        $objItem  = $objXmlDoc->getElementsByTagName('item')->item(0);
        $baseItem = $baseRssDoc->getElementsByTagName('item')->item(0);

        $this->assertTrue($objItem->hasChildNodes());

        $this->assertEquals(
            $baseItem->getElementsByTagName('title')->item(0)->nodeValue,
            $objItem->getElementsByTagName('title')->item(0)->nodeValue
        );

        $this->assertEquals(
            $baseItem->getElementsByTagName('link')->item(0)->nodeValue,
            $objItem->getElementsByTagName('link')->item(0)->nodeValue
        );

    }
}