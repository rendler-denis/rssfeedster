<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Classes\DataSource;

use KoderHut\RssFeedster\Classes\Contracts\IDataSource;

use RainLab\Blog\Models\Post;

/**
 * Class PostsSource
 *
 * Feed data source retrieving blog posts.
 * Requires: RainLab.Blog plug-in
 *
 * @package KoderHut\RssFeedster\Classes\DataSource
 */
class PostsSource
    implements IDataSource
{
    /**
     * Maximum number of items to display in the feed
     */
    const MAX_NO_ITEMS = 10;

    /**
     * Cache for the data
     *
     * @var array|mixed
     */
    protected $data = [];

    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct($params = null)
    {
        if (null === $params) {
            return $this;
        }

        $this->page = $params['page'];
        $this->controller = $params['controller'];
    }

    /**
     * Load the blog posts
     *
     * @return mixed
     */
    public function loadData($maxItems = self::MAX_NO_ITEMS)
    {
        $posts = null;

        if (!empty($this->data)) {
            return $this->data;
        }
        $model = new Post();
        $posts = $model->listFrontEnd([
            'sort'    => 'published_at DESC',
            'perPage' => $maxItems,
        ]);

        foreach ($posts as $post) {
            $post->setUrl($this->page, $this->controller);
        }

        return $this->data = $posts;
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