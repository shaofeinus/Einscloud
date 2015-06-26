<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 26/6/2015
 * Time: 2:24 PM
 */

define("IMG_PATH", "img/");
define("TXT_PATH", "txt/");
define("IMG_EXT", ".jpg");
define("TXT_EXT", ".txt");
define("NRIC_EXT", "_nric");
define("NAME_EXT", "_name");
define("RACE_EXT", "_race");
define("DOB_EXT", "_dob");
define("GENDER_EXT", "_gender");
define("ADDRESS_EXT", "_address");
define("CONVER_BW_PART_CMD", " -type grayscale -clone 0 -type grayscale -negate -lat 20x20+10% -compose copy_opacity -composite -negate -auto-orient ");

if(isset($_POST['img']) && isset($_POST['side'])) {
    $side = $_POST['side'];
    $img = $_POST['img'];

    $fileID = saveImage($img);
    trimImage($fileID);

    if($fileID) {
        echo json_encode(processImage($fileID, $side));
    }
}

function trimImage($fileID) {
    $command = "convert ".
        IMG_PATH . $fileID . IMG_EXT .
        " -bordercolor \"#000000\" -border 1X1 -fuzz 80% -trim +repage ".
        IMG_PATH . $fileID . IMG_EXT;
    exec($command);
}

function processImage($fileID, $side) {

    cropSections($fileID, $side);
    $results = interpretSections($fileID, $side);
    removeImgFiles($fileID, $side);
    return $results;
}

function cropSections($fileID, $side)
{
    if ($side == "front") {
        crop("nric", $fileID);
        crop("name", $fileID);
        crop("race", $fileID);
        crop("dob", $fileID);
        crop("gender", $fileID);
    }

    if ($side == "back") {
        crop("address", $fileID);
    }
}

function crop($section, $fileID) {

    // Constants
    $IC_SPECS = array("width" => 9.8, "height" => 6.2);
    $NRIC_SPECS = array("width" => 3.0, "height" => 0.6, "xOffset" => 3.6, "yOffset" => 0.7);
    $NAME_SPECS = array("width" => 6.0, "height" => 0.5, "xOffset" => 3.4, "yOffset" => 2.3);
    $RACE_SPECS = array("width" => 4.0, "height" => 0.4, "xOffset" => 3.4, "yOffset" => 4.1);
    $DOB_SPECS = array("width" => 2.0, "height" => 0.4, "xOffset" => 3.4, "yOffset" => 4.7);
    $GENDER_SPECS = array("width" => 0.5, "height" => 0.4, "xOffset" => 5.4, "yOffset" => 4.7);
    $ADDRESS_SPECS = array("width" => 7.0, "height" => 1.2, "xOffset" => 0.7, "yOffset" => 4.9);

    $dimension = getimagesize(IMG_PATH . $fileID . IMG_EXT);
    $img_width = $dimension[0];
    $img_height = $dimension[1];

    switch($section) {
        case "nric":

            $cropWidth = round($NRIC_SPECS["width"]/$IC_SPECS["width"]*$img_width);
            $cropHeight = round($NRIC_SPECS["height"]/$IC_SPECS["height"]*$img_height);
            $xOffset = round($NRIC_SPECS["xOffset"]/$IC_SPECS["width"]*$img_width);
            $yOffset = round($NRIC_SPECS["yOffset"]/$IC_SPECS["height"]*$img_height);

            $command = "convert " .
                IMG_PATH . $fileID . IMG_EXT .
                " +repage -crop " .
                $cropWidth . "x" . $cropHeight . "+" . $xOffset . "+" . $yOffset .
                " +repage " .
                IMG_PATH . $fileID . NRIC_EXT . IMG_EXT;

            exec($command);

            $command = "convert " .
                IMG_PATH . $fileID . NRIC_EXT . IMG_EXT . CONVER_BW_PART_CMD . IMG_PATH . $fileID . NRIC_EXT . IMG_EXT;

            exec($command);

            break;

        case "name":

            $cropWidth = round($NAME_SPECS["width"]/$IC_SPECS["width"]*$img_width);
            $cropHeight = round($NAME_SPECS["height"]/$IC_SPECS["height"]*$img_height);
            $xOffset = round($NAME_SPECS["xOffset"]/$IC_SPECS["width"]*$img_width);
            $yOffset = round($NAME_SPECS["yOffset"]/$IC_SPECS["height"]*$img_height);

            $command = "convert " .
                IMG_PATH . $fileID . IMG_EXT .
                " +repage -crop " .
                $cropWidth . "x" . $cropHeight . "+" . $xOffset . "+" . $yOffset .
                " +repage " .
                IMG_PATH . $fileID . NAME_EXT . IMG_EXT;

            exec($command);

            $command = "convert " .
                IMG_PATH . $fileID . NAME_EXT . IMG_EXT . CONVER_BW_PART_CMD . IMG_PATH . $fileID . NAME_EXT . IMG_EXT;

            exec($command);

            break;
        case "race":

            $cropWidth = round($RACE_SPECS["width"]/$IC_SPECS["width"]*$img_width);
            $cropHeight = round($RACE_SPECS["height"]/$IC_SPECS["height"]*$img_height);
            $xOffset = round($RACE_SPECS["xOffset"]/$IC_SPECS["width"]*$img_width);
            $yOffset = round($RACE_SPECS["yOffset"]/$IC_SPECS["height"]*$img_height);

            $command = "convert " .
                IMG_PATH . $fileID . IMG_EXT .
                " +repage -crop " .
                $cropWidth . "x" . $cropHeight . "+" . $xOffset . "+" . $yOffset .
                " +repage " .
                IMG_PATH . $fileID . RACE_EXT . IMG_EXT;

            exec($command);

            $command = "convert " .
                IMG_PATH . $fileID . RACE_EXT . IMG_EXT . CONVER_BW_PART_CMD . IMG_PATH . $fileID . RACE_EXT . IMG_EXT;

            exec($command);

            break;

        case "dob":

            $cropWidth = round($DOB_SPECS["width"]/$IC_SPECS["width"]*$img_width);
            $cropHeight = round($DOB_SPECS["height"]/$IC_SPECS["height"]*$img_height);
            $xOffset = round($DOB_SPECS["xOffset"]/$IC_SPECS["width"]*$img_width);
            $yOffset = round($DOB_SPECS["yOffset"]/$IC_SPECS["height"]*$img_height);

            $command = "convert " .
                IMG_PATH . $fileID . IMG_EXT .
                " +repage -crop " .
                $cropWidth . "x" . $cropHeight . "+" . $xOffset . "+" . $yOffset .
                " +repage " .
                IMG_PATH . $fileID . DOB_EXT . IMG_EXT;

            exec($command);

            $command = "convert " .
                IMG_PATH . $fileID . DOB_EXT . IMG_EXT . CONVER_BW_PART_CMD . IMG_PATH . $fileID . DOB_EXT . IMG_EXT;

            exec($command);

            break;

        case "gender":

            $cropWidth = round($GENDER_SPECS["width"]/$IC_SPECS["width"]*$img_width);
            $cropHeight = round($GENDER_SPECS["height"]/$IC_SPECS["height"]*$img_height);
            $xOffset = round($GENDER_SPECS["xOffset"]/$IC_SPECS["width"]*$img_width);
            $yOffset = round($GENDER_SPECS["yOffset"]/$IC_SPECS["height"]*$img_height);

            $command = "convert " .
                IMG_PATH . $fileID . IMG_EXT .
                " +repage -crop " .
                $cropWidth . "x" . $cropHeight . "+" . $xOffset . "+" . $yOffset .
                " +repage " .
                IMG_PATH . $fileID . GENDER_EXT . IMG_EXT;

            exec($command);

            $command = "convert " .
                IMG_PATH . $fileID . GENDER_EXT . IMG_EXT . CONVER_BW_PART_CMD . IMG_PATH . $fileID . GENDER_EXT . IMG_EXT;

            exec($command);

            break;

        case "address":

            $cropWidth = round($ADDRESS_SPECS["width"]/$IC_SPECS["width"]*$img_width);
            $cropHeight = round($ADDRESS_SPECS["height"]/$IC_SPECS["height"]*$img_height);
            $xOffset = round($ADDRESS_SPECS["xOffset"]/$IC_SPECS["width"]*$img_width);
            $yOffset = round($ADDRESS_SPECS["yOffset"]/$IC_SPECS["height"]*$img_height);

            $command = "convert " .
                IMG_PATH . $fileID . IMG_EXT .
                " +repage -crop " .
                $cropWidth . "x" . $cropHeight . "+" . $xOffset . "+" . $yOffset .
                " +repage " .
                IMG_PATH . $fileID . ADDRESS_EXT . IMG_EXT;

            exec($command);

            $command = "convert " .
                IMG_PATH . $fileID . ADDRESS_EXT . IMG_EXT . CONVER_BW_PART_CMD . IMG_PATH . $fileID . ADDRESS_EXT . IMG_EXT;

            exec($command);

            break;
        default:
            break;
    }
}

function interpretSections($fileID, $side) {

    $results = array();

    if($side == "front") {
        $results["nric"] = interpret("nric", $fileID);
        $results["name"] = interpret("name", $fileID);
        $results["race"] = interpret("race", $fileID);
        $results["dob"] = interpret("dob", $fileID);
        $results["gender"] = interpret("gender", $fileID);
    }

    if($side == "back") {
        $results["address"] = interpret("address", $fileID);
    }

    return $results;
}

function interpret($section, $fileID) {
    switch($section) {
        case "nric":
            $command = "tesseract " . IMG_PATH . $fileID . NRIC_EXT . IMG_EXT . " " . TXT_PATH . $fileID;
            exec($command);
            break;
        case "name":
            $command = "tesseract " . IMG_PATH . $fileID . NAME_EXT . IMG_EXT . " " . TXT_PATH . $fileID;
            exec($command);
            break;
        case "race":
            $command = "tesseract " . IMG_PATH . $fileID . RACE_EXT . IMG_EXT . " " . TXT_PATH . $fileID;
            exec($command);
            break;
        case "dob":
            $command = "tesseract " . IMG_PATH . $fileID . DOB_EXT . IMG_EXT . " " . TXT_PATH . $fileID;
            exec($command);
            break;
        case "gender":
            $command = "tesseract " . IMG_PATH . $fileID . GENDER_EXT . IMG_EXT . " " . TXT_PATH . $fileID;
            exec($command);
            break;
        case "address":
            $command = "tesseract " . IMG_PATH . $fileID . ADDRESS_EXT . IMG_EXT . " " . TXT_PATH . $fileID ;
            exec($command);
            break;
        default:
            break;
    }

    return readTxtFile($fileID);
}

function readTxtFile($fileID) {
    $txtFile = fopen(TXT_PATH . $fileID. TXT_EXT, "r");
    if(filesize(TXT_PATH . $fileID. TXT_EXT) > 0) {
        $contents = fread($txtFile, filesize(TXT_PATH . $fileID. TXT_EXT));
    } else {
        $contents = "";
    }
    fclose($txtFile);
    unlink(TXT_PATH . $fileID. TXT_EXT);
    return $contents;
}


function saveImage($img) {
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $data = base64_decode($img);

    $randomId = rand();
    $filePath = IMG_PATH . $randomId . IMG_EXT;

    $success = file_put_contents($filePath, $data);

    return $success ? $randomId : null;
}

function removeImgFiles($fileID, $side) {

    unlink(IMG_PATH . $fileID . IMG_EXT);

    if ($side == "front") {
        removeImage("nric", $fileID);
        removeImage("name", $fileID);
        removeImage("race", $fileID);
        removeImage("dob", $fileID);
        removeImage("gender", $fileID);
    }

    if ($side == "back") {
        removeImage("address", $fileID);
    }
}

function removeImage($section, $fileID) {
    switch ($section) {
        case "nric":
            $filePath = IMG_PATH . $fileID . NRIC_EXT . IMG_EXT;
            break;
        case "name":
            $filePath = IMG_PATH . $fileID . NAME_EXT . IMG_EXT;
            break;
        case "race":
            $filePath = IMG_PATH . $fileID . RACE_EXT . IMG_EXT;
            break;
        case "dob":
            $filePath = IMG_PATH . $fileID . DOB_EXT . IMG_EXT;
            break;
        case "gender":
            $filePath = IMG_PATH . $fileID . GENDER_EXT . IMG_EXT;
            break;
        case "address":
            $filePath = IMG_PATH . $fileID . ADDRESS_EXT . IMG_EXT;
            break;
        default:
            $filePath = "";
            break;
    }

    if(!empty($filePath)) {
        unlink($filePath);
    }
}

?>