<?php

return [
    /*
|--------------------------------------------------------------------------
| Default Connection Name
|--------------------------------------------------------------------------
|
| Here you may specify which of the connections below you wish to use as
| your default connection for all work. Of course, you may use many
| connections at once using the manager class.
|
*/

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Dropbox Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => array(
        'main' => array(
            'token'  => 'TqExHyJV1X4AAAAAAAAAChEtF-ZPRiEVwxqzfyI8VURf5Z21MaqL3lGAP8MRLN-F',
            'app'    => 'TreLegalTestApp2',
            'process_dir' => '/TRE/royalty_publisher_uploader',
            'processed_dir' => '/TRE/royalty_publisher_uploader_processed'
        ),
    ),
];
