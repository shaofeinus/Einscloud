<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 1/7/2015
 * Time: 5:26 PM
 */

define("IMG_EXT", ".jpg");
define("TEMP_IMG_PATH", "temp/img/");
define("PERM_IMG_PATH", "perm/img/");

if(isset($_POST["icFrontFileName"]) && isset($_POST["icBackFileName"]) && isset($_POST["nric"])) {
    $icFontFileName = $_POST["icFrontFileName"];
    $icBackFileName = $_POST["icBackFileName"];
    $nric = $_POST["nric"];

    mkdir(PERM_IMG_PATH .  $nric);
    $nricDir = $nric . "/";

    $result = array();

    if($icFontFileName !== "") {
        if(rename(TEMP_IMG_PATH . $icFontFileName . IMG_EXT, PERM_IMG_PATH . $nricDir . "front" . IMG_EXT)){
            $result["front"] = "moved";
        } else {
            $result["front"] = "not moved";
        }
    } else {
        $result["front"] = "no image captured";
    }

    if($icBackFileName !== "") {
        if(rename(TEMP_IMG_PATH . $icBackFileName . IMG_EXT, PERM_IMG_PATH . $nricDir . "back" . IMG_EXT)) {
            $result["back"] = "moved";
        } else {
            $result["back"] = "not moved";
        }
    } else {
        $result["back"] = "no image captured";
    }

    echo json_encode($result);

} else {
    echo json_encode("no variables set");
}

?>