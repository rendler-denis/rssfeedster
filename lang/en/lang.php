<?php

return [
    'plugin'    => [
        'name'        => 'KoderHut - RSSFeedster',
        'description' => 'RSS Feed on-the-fly generator.',
        'namespace'   => 'KoderHut',
    ],

    'settings'  => [
        'base'   => [
            'description' => 'Manage RSSFeedster base settings.',
        ],
        'tabs'   => [
            'general_title' => 'General Options',
            'posts_title'   => 'Posts Options',
        ],

        'fields' => [
            'feed_url' => [
                'label'       => 'RSS Feed URL (without domain)',
                'comment'     => 'The URL you want assigned to the RSS feed without the install path',
                'description' => '',
            ],

            'feed_title' => [
                'label'       => 'RSS Feed Title',
                'comment'     => 'The title of your RSS feed (can also be the site name)',
                'description' => 'The title of your RSS feed (can also be the site name)',
                'placeholder' => '',
            ],

            'feed_description' => [
                'label'       => 'Short Description of The RSS Feed',
                'comment'     => 'A short description of the website that will be displayed on the feed',
                'description' => 'A short description of the website that will be displayed on the feed',
            ],

            'feed_category' => [
                'label'       => 'RSS Feed Category',
                'comment'     => 'The category element is used to specify a category for your feed.',
                'description' => 'The category element makes it possible for RSS aggregators to group sites based on category.',
                'placeholder' => '',
            ],

            'feed_copyright' => [
                'label'       => 'RSS Feed Copyright Notice',
                'comment'     => 'The copyright element notifies about copyrighted material.',
                'description' => 'The copyright element notifies about copyrighted material.',
                'placeholder' => '',
            ],

            'feed_language' => [
                'label'       => 'RSS Feed Language',
                'comment'     => 'The language element is used to specify the language used to write your document.',
                'description' => 'The language element makes it possible for RSS aggregators to group sites based on language',
                'placeholder' => '',
            ],

            'post_max_number' => [
                'label'       => 'Max No. of Posts',
                'comment'     => 'The maximum number of posts to display into the feed. Could generate overload if the number is to high! Use -1 to disable this limit.',
                'description' => 'The maximum number of posts to display into the feed. Could generate overload if the number is to high!',
            ],

            'post_full_content' => [
                'label'       => 'Display Full Content',
                'comment'     => 'Display the full post content into the feed or an excerpt',
                'description' => 'Display the full post content into the feed or an excerpt',
            ],

            'post_page' => [
                'label'       => 'Post page',
                'comment'     => 'Set the page used to render the post',
                'description' => 'Set the page used to render the post',
            ],

        ],

    ],

];
