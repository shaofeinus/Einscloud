<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 26/6/2015
 * Time: 2:24 PM
 */


define('X_OFFSET', "xOffset");
define('Y_OFFSET', "yOffset");
define('HEIGHT', "height");
define('WIDTH', "width");

define('IC', "IC");
define('ADDRESS', "address");
define('GENDER', "gender");
define('DOB', "dob");
define('RACE', "race");
define('NAME', "name");
define('NRIC', "nric");

define("IMG_PATH", "img/");
define("TXT_PATH", "txt/");
define("IMG_EXT", ".jpg");
define("TXT_EXT", ".txt");

define('CONVERT_TRIM_PART_CMD', " -bordercolor \"#000000\" -border 1X1 -fuzz 80% -trim +repage ");
define('CONVERT_BW_PART_CMD_1', " -type grayscale -clone 0 -type grayscale -negate -lat 20x20+10% -compose copy_opacity -composite -negate -auto-orient ");
define('CONVERT_BW_PART_CMD_2', " -threshold 50% ");

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
    //$command = "convert " . IMG_PATH . $fileID . IMG_EXT . CONVERT_BW_PART_CMD_2 . IMG_PATH . $fileID . IMG_EXT;
    //exec($command);
    $command = "convert " . IMG_PATH . $fileID . IMG_EXT . CONVERT_TRIM_PART_CMD . IMG_PATH . $fileID . IMG_EXT;
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
        crop(NRIC, $fileID);
        crop(NAME, $fileID);
        crop(RACE, $fileID);
        crop(DOB, $fileID);
        crop(GENDER, $fileID);
    }

    if ($side == "back") {
        crop(ADDRESS, $fileID);
    }
}

function crop($section, $fileID) {

    // Specs for each section
    $SPECS = array();
    $SPECS[IC] = array(WIDTH => 9.8, HEIGHT => 6.2);
    $SPECS[NRIC] = array(WIDTH => 3.0, HEIGHT => 0.6, X_OFFSET => 3.6, Y_OFFSET => 0.7);
    $SPECS[NAME] = array(WIDTH => 6.0, HEIGHT => 0.5, X_OFFSET => 3.4, Y_OFFSET => 2.3);
    $SPECS[RACE] = array(WIDTH => 4.0, HEIGHT => 0.4, X_OFFSET => 3.4, Y_OFFSET => 4.1);
    $SPECS[DOB] = array(WIDTH => 2.0, HEIGHT => 0.4, X_OFFSET => 3.4, Y_OFFSET => 4.7);
    $SPECS[GENDER] = array(WIDTH => 0.5, HEIGHT => 0.4, X_OFFSET => 5.4, Y_OFFSET => 4.7);
    $SPECS[ADDRESS] = array(WIDTH => 7.0, HEIGHT => 1.2, X_OFFSET => 0.7, Y_OFFSET => 4.9);

    // Dimension of current image
    $dimension = getimagesize(IMG_PATH . $fileID . IMG_EXT);
    $img_width = $dimension[0];
    $img_height = $dimension[1];

    // Factors to arrive at crop specs
    $widthFactor = $img_width / $SPECS[IC][WIDTH];
    $heightFactor = $img_height / $SPECS[IC][HEIGHT];

    // Obtain crop specs for section
    $cropWidth = round($SPECS[$section][WIDTH] * $widthFactor);
    $cropHeight = round($SPECS[$section][HEIGHT] * $heightFactor);
    $xOffset = round($SPECS[$section][X_OFFSET] * $widthFactor);
    $yOffset = round($SPECS[$section][Y_OFFSET] * $heightFactor);

    // Crops out sections of the image
    $command = "convert " .
        IMG_PATH . $fileID . IMG_EXT .
        " +repage -crop " .
        $cropWidth . "x" . $cropHeight . "+" . $xOffset . "+" . $yOffset .
        " +repage " .
        IMG_PATH . $fileID . $section . IMG_EXT;

    exec($command);

    // Make image B/W
    $command = "convert " .
        IMG_PATH . $fileID . $section . IMG_EXT . CONVERT_BW_PART_CMD_1 . IMG_PATH . $fileID . $section . IMG_EXT;

    exec($command);

}

function interpretSections($fileID, $side) {

    $results = array();

    if($side == "front") {
        $results[NRIC] = interpret(NRIC, $fileID);
        $results[NAME] = interpret(NAME, $fileID);
        $results[RACE] = interpret(RACE, $fileID);
        $results[DOB] = interpret(DOB, $fileID);
        $results[GENDER] = interpret(GENDER, $fileID);
    }

    if($side == "back") {
        $results[ADDRESS] = interpret(ADDRESS, $fileID);
    }

    return $results;
}

function interpret($section, $fileID) {
    $command = "tesseract " . IMG_PATH . $fileID . $section . IMG_EXT . " " . TXT_PATH . $fileID ;
    exec($command);
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
        removeImage(NRIC, $fileID);
        removeImage(NAME, $fileID);
        removeImage(RACE, $fileID);
        removeImage(DOB, $fileID);
        removeImage(GENDER, $fileID);
    }

    if ($side == "back") {
        removeImage(ADDRESS, $fileID);
    }
}

function removeImage($section, $fileID) {
    $filePath = IMG_PATH . $fileID . $section . IMG_EXT;
    unlink($filePath);
}

?>