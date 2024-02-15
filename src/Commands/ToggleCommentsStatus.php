<?php

namespace Nmolham\PhpMeetupWpCli\Commands;

use Exception;
use WP_CLI;
use WP_Post;

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
                    'options'     => ['open', 'closed', 'toggle', 'random'],
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
        $postIds = array_filter(explode(',', WP_CLI\Utils\get_flag_value($assocArgs, 'only')));

        $targetStatus = $args[0];

        $posts = get_posts($postIds ? ['post__in' => $postIds] : ['nopaging' => true]);

        $progressBar = WP_CLI\Utils\make_progress_bar('Updating comments status', count($posts));

        foreach ($posts as $post) {
            $newStatus = $this->determineNewStatus($targetStatus, $post);

            if ($this->shouldNotUpdateStatus($post, $newStatus)) {
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

    protected function determineNewStatus(string $targetStatus, WP_Post $post) : string
    {
        $newStatus = $targetStatus;

        if ('toggle' === $targetStatus) {
            $newStatus = 'open' === $post->comment_status ? 'closed' : 'open';
        }

        if ('random' === $targetStatus) {
            $newStatus = random_int(0, 100) >= 50 ? 'open' : 'closed';
        }

        return $newStatus;
    }

    protected function shouldNotUpdateStatus(WP_Post $post, string $newStatus) : bool
    {
        return $post->comment_status === $newStatus;
    }
}
