<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Classes;

use October\Rain\Exception\ApplicationException;

use KoderHut\RssFeedster\Classes\Contracts\IDataSource,
    KoderHut\RssFeedster\Classes\Contracts\IRenderer,
    KoderHut\RssFeedster\Models\Settings,
    KoderHut\RssFeedster\Classes\Support\Facades\Renderer,
    KoderHut\RssFeedster\Classes\Support\Facades\DataSource;

/**
 * Class Feedster
 * Service class in charge of collecting the feed data
 * and rendering it
 *
 * @package KoderHut\RssFeedster\Classes
 */
class Feedster
{
    /**
     * The feed generator name
     */
    const FEED_GENERATOR_NAME = 'KoderHut.eu - RSSFeedster for OctoberCMS';

    /**
     * Feed title
     *
     * @var string
     */
    protected $feedTitle       = '';

    /**
     * Maximum number of rows to retrieve and render
     *
     * @var int
     */
    protected $dataRowsLimit   = 1;

    /**
     * Either to display the full content
     *
     * @var bool
     */
    protected $fullContent     = false;

    /**
     * Data source for retrieving the feed data
     *
     * @var IDataSource
     */
    protected $source          = null;

    /**
     * Feed renderer
     *
     * @var IRenderer
     */
    protected $renderer        = null;

    /**
     * Constructor
     *
     * @throws ApplicationException
     */
    public function __construct(IDataSource $source = null, IRenderer $renderer = null, Settings $config = null)
    {
        $config = $config instanceof Settings ? $config : Settings::instance();

        $this->validateSettings($config);

        $this->feedTitle       = $config->feed_title;
        $this->dataRowsLimit   = $config->post_max_number;
        $this->fullContent     = $config->post_full_content;

        $this->source          = $source ?: DataSource::getFacadeRoot();
        $this->renderer        = $renderer ?: Renderer::getFacadeRoot();
    }

    /**
     * Validate required settings
     *
     * @param Settings $settings
     *
     * @return bool
     *
     * @throws ApplicationException
     */
    protected function validateSettings(Settings $settings)
    {
        if (empty($settings->feed_title)) {
            throw new ApplicationException('Feed Title is required!');
        }

        if ($settings->post_max_number <= 0) {
            $this->dataRowsLimit = 1000;
        }

        return true;
    }

    /**
     * Set a data source for building the feed
     *
     * @param IDataSource $source
     */
    public function setSource(IDataSource $source)
    {
        $this->source = $source;
    }

    /**
     * Set a renderer for the feed
     *
     * @param Renderer $renderer
     */
    public function setRenderer(IRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Return the rendered feed data
     *
     * @return mixed
     */
    public function getFeed()
    {
        $feedData     = $this->source->getData($this->dataRowsLimit);
        $renderedData = $this->renderer->renderData($feedData);

        return $renderedData;
    }

    /**
     * Return the content-type for this renderer
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->renderer->getContentType();
    }
}