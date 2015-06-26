<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 24/6/2015
 * Time: 5:29 PM
 */

if(isset($_POST['img']) && isset($_POST['fileName'])) {

    $img = $_POST['img'];
    $fileName = $_POST['fileName'];

    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $data = base64_decode($img);
    $file = "img/$fileName.jpeg";

    $success = file_put_contents($file, $data);
    echo $success ? $file : 'Unable to save the file.';
}

?>
