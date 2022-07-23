#!/usr/bin/env php

<?php

require_once __DIR__ . '/../vendor/autoload.php';

$data = fileGetContentsCurl('https://chromium-i18n.appspot.com/ssl-address/data');

echo "Fetching country list...\r\n";
$countries = explode('~',json_decode($data,true)['countries']);

$responses = [];
echo "Fetching country data...\r\n";
foreach ($countries as $country) {
    usleep(1000000);
    $responses[] = fileGetContentsCurl("https://chromium-i18n.appspot.com/ssl-address/data/$country");
}

echo "Compiling data...\r\n";
$examples = [];
$patterns = [];

foreach ($responses as $response) {
    $countryZipCodeData = json_decode($response,true);
}

echo print_r($countries,true);
die();

function fileGetContentsCurl($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}