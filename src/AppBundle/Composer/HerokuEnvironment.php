<?php

namespace AppBundle\Composer;

use Composer\Script\Event;

class HerokuEnvironment {

    /**
     * Populate Heroku environment
     *
     * @param Event $event Event
     */
    public static function populateEnvironment(Event $event) {
        $url = parse_url(getenv('JAWSDB_URL')); // If MySQL is chosen

        $io = $event->getIO();

        if ($url && getenv('JAWSDB_URL')) {
            $io->write('JAWSDB_URL=' . getenv('JAWSDB_URL'));

            putenv("database_host={$url['host']}");
            putenv("database_user={$url['user']}");
            putenv("database_password={$url['pass']}");
            putenv("database_port={$url['port']}");

            $db = substr($url['path'], 1);
            putenv("database_name={$db}");
        }

        
    }

}
