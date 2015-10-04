<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Tests\Classes;

use App,
    PluginTestCase;

use KoderHut\RssFeedster\Classes\Feedster,
    KoderHut\RssFeedster\Classes\Support\Facades\DataSource,
    KoderHut\RssFeedster\Classes\Support\Facades\Renderer,
    KoderHut\RssFeedster\Models\Settings;

use KoderHut\RssFeedster\Tests\Mock\DataSource\MockDataSource;
use KoderHut\RssFeedster\Tests\Mock\View\MockBlankRenderer;
use KoderHut\RssFeedster\Tests\Mock\View\MockJsonRenderer;


/**
 * Class FeedsterTest
 * @package KoderHut\RssFeedster\Tests\Classes
 */
class FeedsterTest
    extends PluginTestCase
{

    /**
     * Set up the test case
     */
    public function setUp()
    {
        parent::setUp();

        /**
         * Create a mock-up of the Settings object
         * TODO: find a better way to mock this object
         */
        $config = Settings::instance();
        $config->setAttribute('feed_title', 'test');

        /**
         * Set up the data source for the feed
         */
        App::bind('KoderHut\RssFeedster\DataSource', function($app)
        {
            return new MockDataSource();
        });

        /**
         * Set up the feed renderer
         */
        App::bind('KoderHut\RssFeedster\Renderer', function($app)
        {
            $config = [
                'description' => 'feed description',
                'title'       => 'feed title',
                'category'    => 'feed category',
                'copyright'   => 'feed copyright',
                'language'    => 'feed language',
                'link'        => 'feed url',
            ];

            return new MockJsonRenderer($config);
        });

        $this->feed = new Feedster(null, null, $config);
    }

    /**
     * Test object injection replacement
     */
    public function testObjectReplacement()
    {
        $this->assertInstanceOf(
            'KoderHut\RssFeedster\Tests\Mock\DataSource\MockDataSource',
            DataSource::getFacadeRoot()
        );

        $this->assertInstanceOf(
            'KoderHut\RssFeedster\Tests\Mock\View\MockJsonRenderer',
            Renderer::getFacadeRoot()
        );
    }

    /**
     * Test that we successfully changed the renderer to
     * a JSON renderer
     */
    public function testJsonReplacementRenderer()
    {
        $this->assertJson($this->feed->getFeed());
    }

    /**
     * Test that we successfully changed the sources
     */
    public function testSources()
    {
        $this->feed->setRenderer(new MockBlankRenderer(['customCfgData' => 'data']));
        $feedData = $this->feed->getFeed();

        $this->assertArrayHasKey('customCfgData', $feedData['channel']);

        $this->assertCount(3, $feedData['items']);
    }
}