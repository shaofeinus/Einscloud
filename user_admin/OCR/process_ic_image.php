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

define("TEMP_IMG_PATH", "temp/img/");
define("TEMP_TXT_PATH", "temp/txt/");
define("IMG_EXT", ".jpg");
define("TXT_EXT", ".txt");

define('CONVERT_TRIM_PART_CMD', " -bordercolor \"#000000\" -border 1X1 -fuzz 80% -trim +repage ");
define('CONVERT_BW_PART_CMD_1', " -type grayscale -clone 0 -type grayscale -negate -lat 20x20+10% -compose copy_opacity -composite -negate -auto-orient ");
define('CONVERT_BW_PART_CMD_2', " -threshold 50% ");

if(isset($_POST['img']) && isset($_POST['side']) && isset($_POST['existingFile'])) {
    $side = $_POST['side'];
    $img = $_POST['img'];
    $existingImgFileName = $_POST['existingFile'];

    $fileName = saveImage($img, $existingImgFileName, $side);
    trimImage($fileName);

    if($fileName) {
        $result = array();
        $result["form"] = processImage($fileName, $side);
        $result["fileName"] = $fileName;
        echo json_encode($result);
    } else {
        echo json_encode("error");
    }
}

function trimImage($fileName) {
    //$command = "convert " . IMG_PATH . $fileID . IMG_EXT . CONVERT_BW_PART_CMD_2 . IMG_PATH . $fileID . IMG_EXT;
    //exec($command);
    $command = "convert " . TEMP_IMG_PATH . $fileName . IMG_EXT . CONVERT_TRIM_PART_CMD . TEMP_IMG_PATH . $fileName . IMG_EXT;
    exec($command);
}

function processImage($fileName, $side) {

    cropSections($fileName, $side);
    $results = interpretSections($fileName, $side);
    removeImgFiles($fileName, $side);
    return $results;
}

function cropSections($fileName, $side)
{
    if ($side == "front") {
        crop(NRIC, $fileName);
        crop(NAME, $fileName);
        crop(RACE, $fileName);
        crop(DOB, $fileName);
        crop(GENDER, $fileName);
    }

    if ($side == "back") {
        crop(ADDRESS, $fileName);
    }
}

function crop($section, $fileName) {

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
    $dimension = getimagesize(TEMP_IMG_PATH . $fileName . IMG_EXT);
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
        TEMP_IMG_PATH . $fileName . IMG_EXT .
        " +repage -crop " .
        $cropWidth . "x" . $cropHeight . "+" . $xOffset . "+" . $yOffset .
        " +repage " .
        TEMP_IMG_PATH . $fileName . $section . IMG_EXT;

    exec($command);

    // Make image B/W
    $command = "convert " .
        TEMP_IMG_PATH . $fileName . $section . IMG_EXT . CONVERT_BW_PART_CMD_1 . TEMP_IMG_PATH . $fileName . $section . IMG_EXT;

    exec($command);

}

function interpretSections($fileName, $side) {

    $results = array();

    if($side == "front") {
        $results[NRIC] = interpret(NRIC, $fileName);
        $results[NAME] = interpret(NAME, $fileName);
        $results[RACE] = interpret(RACE, $fileName);
        $results[DOB] = interpret(DOB, $fileName);
        $results[GENDER] = interpret(GENDER, $fileName);
    }

    if($side == "back") {
        $results[ADDRESS] = interpret(ADDRESS, $fileName);
    }

    return $results;
}

function interpret($section, $fileName) {
    $command = "tesseract " . TEMP_IMG_PATH . $fileName . $section . IMG_EXT . " " . TEMP_TXT_PATH . $fileName ;
    exec($command);
    return readTxtFile($fileName);
}

function readTxtFile($fileName) {
    $txtFile = fopen(TEMP_TXT_PATH . $fileName. TXT_EXT, "r");
    if(filesize(TEMP_TXT_PATH . $fileName. TXT_EXT) > 0) {
        $contents = fread($txtFile, filesize(TEMP_TXT_PATH . $fileName. TXT_EXT));
    } else {
        $contents = "";
    }
    fclose($txtFile);
    unlink(TEMP_TXT_PATH . $fileName. TXT_EXT);
    return $contents;
}


function saveImage($img, $existingImgFileName, $side) {
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $data = base64_decode($img);

    if($existingImgFileName !== "") {
        unlink(TEMP_IMG_PATH . $existingImgFileName . IMG_EXT);
    }

    $randomId = rand();
    $fileName =  $randomId . $side;
    $filePath = TEMP_IMG_PATH . $fileName . IMG_EXT;
    $success = file_put_contents($filePath, $data);
    chmode($filePath . TEMP_IMG_PATH . $fileName . IMG_EXT,
        fileperms($filePath . TEMP_IMG_PATH . $fileName . IMG_EXT) | 128 + 16 + 2);

    return $success ? $fileName : null;
}

function removeImgFiles($fileName, $side) {

    if ($side == "front") {
        removeImage(NRIC, $fileName);
        removeImage(NAME, $fileName);
        removeImage(RACE, $fileName);
        removeImage(DOB, $fileName);
        removeImage(GENDER, $fileName);
    }

    if ($side == "back") {
        removeImage(ADDRESS, $fileName);
    }
}

function removeImage($section, $fileName) {
    $filePath = TEMP_IMG_PATH . $fileName . $section . IMG_EXT;
    unlink($filePath);
}

?>