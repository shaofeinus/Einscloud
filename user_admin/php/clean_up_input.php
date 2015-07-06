<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by:
 * /einshub/user_admin/php/user_add_new_viewer.php
 * /einshub/user_admin/php/user_edit_profile_functions.php
 * /einshub/user_admin/php/user_add_new_emerg.php
 * /einshub/user_admin/php/user_login.php
 * /einshub/user_admin/php/user_registration_create.php
 * @calls:
 * @description:
 * This is a function to sanitize any input with the intention to defend against XSS attack.
 */

function cleanUpInput($input){
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}