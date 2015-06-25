<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 24/6/2015
 * Time: 5:29 PM
 */

$img = $_POST['img'];

$img = str_replace('data:image/jpeg;base64,', '', $img);
$data = base64_decode($img);
$file = 'img/test.jpeg';
$success = file_put_contents($file, $data);
echo $success ? $file : 'Unable to save the file.';

?>
