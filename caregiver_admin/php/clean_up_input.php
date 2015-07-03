<?php
/**
 * Created by IntelliJ IDEA.
 * User: suheti
 *
 * This is a function to sanitize any input with the intention to defend against XSS attack.
 */

function cleanUpInput($input){
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}