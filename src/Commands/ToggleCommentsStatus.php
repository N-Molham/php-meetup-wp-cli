<?php

namespace Nmolham\PhpMeetupWpCli\Commands;

use Exception;
use WP_CLI;

class ToggleCommentsStatus
{
    /**
     * @throws Exception
     */
    public static function register() : void
    {
        WP_CLI::add_command('toggle-comments-status', static::class, [
            'shortdesc' => 'Toggle comments status for posts',
            'synopsis'  => [
                [
                    'type'        => 'positional',
                    'name'        => 'status',
                    'description' => 'Which status to set the comments to',
                    'options'     => ['open', 'closed', 'toggle'],
                    'optional'    => false,
                ],
                [
                    'type'        => 'assoc',
                    'name'        => 'only',
                    'description' => 'Which posts to update, a comma separated list of post IDs',
                    'default'     => '',
                    'optional'    => true,
                ],
            ],
        ]);
    }

    public function __invoke(array $args, array $assocArgs) : void
    {
        $postIds = explode(',', WP_CLI\Utils\get_flag_value($assocArgs, 'only'));

        $targetStatus = $args[0];
        $toggle = 'toggle' === $targetStatus;

        $posts = get_posts($postIds ? ['post__in' => $postIds] : ['nopaging' => true]);

        $progressBar = WP_CLI\Utils\make_progress_bar('Updating comments status', count($posts));

        foreach ($posts as $post) {
            $newStatus = $targetStatus;

            if ($toggle) {
                $newStatus = 'open' === $post->comment_status ? 'closed' : 'open';
            }

            if ($post->comment_status === $newStatus) {
                $progressBar->tick(1, "Comments status already set for post $post->ID");
                continue;
            }

            wp_update_post([
                'ID'             => $post->ID,
                'comment_status' => $newStatus,
            ]);

            $progressBar->tick(1, "Comments status updated for post $post->ID");
        }

        $progressBar->finish();

        WP_CLI::success('Comments status updated');
    }
}
