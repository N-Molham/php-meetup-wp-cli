<?php

namespace Nmolham\PhpMeetupWpCli;

use Exception;
use Nmolham\PhpMeetupWpCli\Commands\RandomPosts;
use Nmolham\PhpMeetupWpCli\Commands\ToggleCommentsStatus;
use WP_CLI;
use function WP_CLI\Utils\format_items;

class Plugin
{
    /**
     * @throws Exception
     */
    public static function init() : void
    {
        self::initCli();
    }

    /**
     * @return void
     * @throws Exception
     */
    protected static function initCli() : void
    {
        if (! defined('WP_CLI') || ! WP_CLI) {
            return;
        }

        RandomPosts::register();
        ToggleCommentsStatus::register();
    }
}
