<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 23/6/2015
 * Time: 5:24 PM
 */

$command = "convert $inFile -type grayscale -clone 0 -type grayscale -negate -lat 50x50+7% -compose copy_opacity" .
"-composite -fill \"#00000000\" - negate -auto_orient $outFile";

echo exec("tesseract img/ic.png out");

?>