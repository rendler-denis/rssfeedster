<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster;

use Route,
    Url;

use Cms\Classes\Controller;

use System\Classes\PluginBase,
    System\Classes\PluginManager;

use KoderHut\RssFeedster\Classes\Feedster,
    KoderHut\RssFeedster\Classes\DataSource\PostsSource,
    KoderHut\RssFeedster\Classes\View\XmlRenderer,
    KoderHut\RssFeedster\Models\Settings,
    KoderHut\RssFeedster\Classes\Support\Adapters\BlogPostXmlAdapter,
    KoderHut\RssFeedster\Classes\Contracts\IAdapter;


/**
 * RssFeed Plugin Information File
 */
class Plugin
    extends PluginBase
{
    /**
     * Namespace const
     */
    const KODERHUT_RSSFEEDSTER_NS = 'KoderHut\RssFeedster';

    /**
     * Define the plug-in dependencies
     *
     * @var array Plugin dependencies
     */
    public $require = ['RainLab.Blog'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'koderhut.rssfeedster::lang.plugin.name',
            'description' => 'koderhut.rssfeedster::lang.plugin.description',
            'author'      => 'Denis-Florin Rendler (KoderHut)',
            'icon'        => 'icon-rss',
            'homepage'    => 'https://github.com/rainlab/blog-plugin',
        ];
    }

    /**
     * Set up the route for the RSS builder
     */
    public function boot()
    {
        $rssUrl = Settings::get('feed_url');

        Route::get($rssUrl, 'KoderHut\RssFeedster\Controllers\Rss@buildRssFeed');

        $this->app->bind('KoderHut\RssFeedster\Feed', function($app)
        {
            return new Feedster();
        });
    }

    /**
     * Register our data source and renderer
     */
    public function register()
    {
        /**
         * Set up the data source for the feed
         */
        $this->app->bind('KoderHut\RssFeedster\DataSource', function($app)
        {
            $config['page']            = Settings::get('post_page');
            $config['comments_anchor'] = Settings::get('comments_anchor');
            $config['controller']      = new Controller();

            return new PostsSource($config);
        });

        /**
         * Set up the adapter interface between the XML renderer and
         * the blog posts data
         */
        $this->app->bind(IAdapter::DI_NAMESPACE, function () {
            return new BlogPostXmlAdapter();
        });

        /**
         * Set up the feed renderer
         */
        $this->app->bind('KoderHut\RssFeedster\Renderer', function($app)
        {
            $config = [
                'description' => Settings::get('feed_description'),
                'title'       => Settings::get('feed_title'),
                'category'    => Settings::get('feed_category'),
                'copyright'   => Settings::get('feed_copyright'),
                'language'    => Settings::get('feed_language'),
                'link'        => Url::action('KoderHut\RssFeedster\Controllers\Rss@buildRssFeed'),
            ];
            $pluginPath  = PluginManager::instance()->getPluginPath(Plugin::KODERHUT_RSSFEEDSTER_NS);
            $xmlTemplate = $pluginPath . DIRECTORY_SEPARATOR . 'resources'
                . DIRECTORY_SEPARATOR . 'feed-template.xml';

            return
                new XmlRenderer(
                    $config,
                    file_get_contents($xmlTemplate),
                    $this->app->make(IAdapter::DI_NAMESPACE)
                );
        });

        return;
    }

    /**
     * Register the plug-in settings
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'koderhut.rssfeedster::lang.plugin.name',
                'description' => 'koderhut.rssfeedster::lang.settings.base.description',
                'icon'        => 'icon-rss',
                'class'       => 'KoderHut\RssFeedster\Models\Settings',
                'order'       => 500,
                'keywords'    => 'rss feed',
                'category'    => 'KoderHut',
                'permissions' => ['koderhut.rssfeedster.access_config'],
            ]
        ];
    }

    /**
     * Register plug-in permissions
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'koderhut.rssfeedster.access_config' => [
                'label' => 'koderhut.rssfeedster::lang.messages.permissions.access_config_label',
                'tab'   => 'koderhut.rssfeedster::lang.plugin.name'
            ],
        ];
    }
}
