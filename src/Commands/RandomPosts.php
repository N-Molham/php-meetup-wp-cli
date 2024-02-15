<?php

namespace Nmolham\PhpMeetupWpCli\Commands;

use Exception;
use WP_CLI;
use function WP_CLI\Utils\format_items;
use function WP_CLI\Utils\get_flag_value;

class RandomPosts
{
    /**
     * @throws Exception
     */
    public static function register() : void
    {
        WP_CLI::add_command('random-posts', static::class, [
            'shortdesc' => 'Get 10 random posts',
            'synopsis'  => [
                [
                    'type'        => 'assoc',
                    'name'        => 'count',
                    'description' => 'Number of posts to return',
                    'optional'    => true,
                    'default'     => 10,
                ],
                [
                    'type'        => 'assoc',
                    'name'        => 'format',
                    'description' => 'Format to return the posts in',
                    'optional'    => true,
                    'options'     => ['table', 'json', 'csv', 'yaml', 'count'],
                    'default'     => 'table',
                ],
            ],
        ]);
    }

    public function __invoke(array $args, array $assocArgs) : void
    {
        $count = get_flag_value($assocArgs, 'count');

        format_items(
            get_flag_value($assocArgs, 'format'),
            get_posts(['orderby' => 'rand', 'posts_per_page' => $count]),
            ['ID', 'post_title', 'post_status', 'post_date']
        );
    }
}
