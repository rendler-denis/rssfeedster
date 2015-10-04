<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 22/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Tests\Mock\DataSource;

use Model;

use KoderHut\RssFeedster\Classes\Contracts\IDataSource;

/**
 * Class PostsSource
 *
 * Feed data source retrieving blog posts.
 * Requires: RainLab.Blog plug-in
 *
 * @package KoderHut\RssFeedster\Classes\DataSource
 */
class MockDataSource
    implements IDataSource
{
    /**
     * Cache for the data
     *
     * @var array|mixed
     */
    protected $data = [];

    /**
     * Load the blog posts
     *
     * @return mixed
     */
    public function loadData()
    {
        $items      = [];
        $attributes = [
            'title'   => 'post title',
            'url'     => 'post url',
            'summary' => 'post summary',
            'pubDate' => 'Tue, 29-Sep-2015',
        ];

        if (!empty($this->data)) {
            return $this->data;
        }

        $items      = [
            Model::make($attributes),
            Model::make($attributes),
            Model::make($attributes),
        ];

        return $this->data = $items;
    }

    /**
     * Retrieve posts from cache or load them and then return them
     *
     * @return mixed
     */
    public function getData()
    {
        if (null !== $this->data && !empty($this->data)) {
            return $this->data;
        }

        $this->data = $this->loadData();

        return $this->data;
    }
}