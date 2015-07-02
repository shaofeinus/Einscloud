<?php
    if (php_sapi_name() != 'cli')
        die("No direct access, bro");
    require_once 'user_admin/php/DB_connect/db_utility.php';

    $deleteQuery = 'delete from ResetPassword';
    make_query($deleteQuery);