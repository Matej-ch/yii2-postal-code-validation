#!/usr/bin/env php

<?php

require_once __DIR__ . '/../vendor/autoload.php';
$baseUrl = "https://chromium-i18n.appspot.com/ssl-address/data";
$data = fileGetContentsCurl($baseUrl);

echo "Fetching country list...\r\n";
$countries = explode('~',json_decode($data,true)['countries']);

$responses = [];
echo "Fetching country data...\r\n";
foreach ($countries as $country) {
    usleep(1000000);
    echo "Country $country\r\n";
    $responses[] = fileGetContentsCurl("$baseUrl/$country");
}

echo "Compiling data...\r\n";
$examples = [];
$patterns = [];

foreach ($responses as $response) {
    $countryZipCodeData = json_decode($response,true);

    if (isset($countryZipCodeData['zip'], $countryZipCodeData['zipex'])) {
        $examples[$countryZipCodeData['key']] = explode(',', $countryZipCodeData['zipex'])[0];
        $patterns[$countryZipCodeData['key']] = "/^(?:{$countryZipCodeData['zip']})$/i";
    } else {
        $patterns[$countryZipCodeData['key']] = null;
    }
}

echo "Writing files...\r\n";
foreach ([
             __DIR__ . '/resources/examples.php' => $examples,
             __DIR__ . '/resources/patterns.php' => $patterns,
         ] as $path => $data) {

    if (!$handle = @fopen($path, 'wb')) {
        echo 'Unable to write to file: ' . $path;
        exit(1);
    }

    fwrite($handle, "<?php
/*
|--------------------------------------------------------------------------
| Laravel Postal Codes Validation
|--------------------------------------------------------------------------
|
| This resource file is generated from Google's Address Validation
| Metadata API. Please do not edit this file directly, pull requests
| containing changes to this file will not be accepted!
|
| For more information on the API refer to Google's public repository:
| https://github.com/google/libaddressinput
|
*/
return [\n");

    $keyValueString = '';
    foreach ($data as $key => $zip) {
        $keyValueString .= "\t'$key' =>'$zip'\n";
    }

    fwrite($handle, stripslashes($keyValueString));
    fwrite($handle, "]\n");
    fclose($handle);
}

echo "Done.\r\n";

function fileGetContentsCurl($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}