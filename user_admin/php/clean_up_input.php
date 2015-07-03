<?php
/**
 * Created by IntelliJ IDEA.
 * User: suheti
 * Date: 2/7/15
 * Time: 5:53 PM
 */

function cleanUpInput($input){
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}