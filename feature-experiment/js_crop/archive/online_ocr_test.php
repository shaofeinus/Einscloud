<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 23/6/2015
 * Time: 5:05 PM
 */

$file = realpath("img/barcode.png");
$ch = curl_init();
$api_key = "3ce89e7a07ea20a05d2983df7415051d";
curl_setopt($ch, CURLOPT_URL, "http://api.newocr.com/v1/upload?key=$api_key");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => '@'.$file));
$result = json_decode(curl_exec($ch));
curl_close ($ch);

$file_id = $result->data->file_id;
$page = $result->data->pages;

$ch = curl_init("http://api.newocr.com/v1/ocr?key=$api_key&file_id=$file_id&page=$page&lang=eng&psm=6");
curl_setopt($ch, CURLOPT_HEADER, 0);
$result = curl_exec($ch);
echo $result;
curl_close($ch);

?>