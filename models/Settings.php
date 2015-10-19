<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Models;

use Model,
    Lang,
    File,
    Yaml as YamlParser;

use System\Classes\PluginManager;

use Cms\Classes\Page;

use KoderHut\RssFeedster\Plugin;

/**
 * Settings Model
 */
class Settings
    extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * @var string
     */
    public $settingsCode = 'koderhut_rssfeedster_settings';

    /**
     * @var string
     */
    public $settingsFields = 'fields.yaml';

    /**
     * Validation rules
     */
    public $rules = [
        'feed_url'     => 'required',
        'feed_title'   => 'required',
    ];


    /**
     * Init default data
     */
    public function initSettingsData()
    {
        $this->feed_title
            = Lang::get('koderhut.rssfeedster::lang.settings.fields.feed_title.default');
        $this->feed_url
            = Lang::get('koderhut.rssfeedster::lang.settings.fields.feed_url.default');
        $this->post_page = '404';
    }

    /**
     * Return the post_page dropdown options
     *
     * @return array
     */
    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Load and return the feed_language dropdown options
     *
     * @return array
     */
    public function getFeedLanguageOptions()
    {
        $pluginPath = PluginManager::instance()->getPluginPath(Plugin::KODERHUT_RSSFEEDSTER_NS);
        $path       = $pluginPath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'language_codes.yaml';

        if (!File::exists($path)) {
            return [];
        }

        $languages = YamlParser::parseFile($path);

        $languages = is_array($languages) ? array_flip($languages) : [];

        return $languages;
    }
}