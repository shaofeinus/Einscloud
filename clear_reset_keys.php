<?php
    debug_backtrace() || die("Death");
    require_once 'user_admin/php/DB_connect/db_utility.php';

    $deleteQuery = 'delete from ResetPassword';
    make_query($deleteQuery);