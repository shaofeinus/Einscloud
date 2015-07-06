<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by:
 *  einshub/caregiver_admin/php/caregiver_edit_phone.php
 *  einshub/caregiver_admin/php/caregiver_edit_username.php
 *  einshub/caregiver_admin/php/caregiver_registration_create.php
 *  einshub/caregiver_admin/php/caregiver_edit_email.php
 *  einshub/caregiver_admin/php/caregiver_login.php
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