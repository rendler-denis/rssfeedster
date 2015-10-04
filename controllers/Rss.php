<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 21/09/15
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

namespace KoderHut\RssFeedster\Controllers;

use App;

use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Controller as ControllerBase;


/**
 * Rss feed builder controller
 */
class Rss
    extends ControllerBase
{
    /**
     * Implement behaviours
     *
     * @var array
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    /**
     * @var string
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string
     */
    public $listConfig = 'config_list.yaml';


    /**
     * Build the RSS feed action
     */
    public function buildRssFeed()
    {
        $feedster = App::make('KoderHut\RssFeedster\Feed');

        return Response::make($feedster->getFeed())
            ->header('Content-Type', $feedster->getContentType());
    }
}
