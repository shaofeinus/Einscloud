<?php
    /**
     * @date-of-doc: 2015-07-06
     * @project-version: v0.2
     * @called-by:
     * @calls:
     *  php/DB_connect/db_utility.php
     * @description:
     *  This file is only accessed by the server. It deletes unused reset keys in a day
     */
    if (php_sapi_name() != 'cli')
        die("No direct access, bro");
    require_once 'user_admin/php/DB_connect/db_utility.php';

    $deleteQuery = 'delete from ResetPassword';
    make_query($deleteQuery);