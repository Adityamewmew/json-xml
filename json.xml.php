<?php
$apiUrl = "https://jsonplaceholder.typicode.com/users";

function arrayToXml($data, &$xmlData) {
    foreach ($data as $key => $value) {
        if (is_numeric($key)) {
            $key = "item$key";
        }
        
        if (is_array($value) || is_object($value)) {
            $subnode = $xmlData->addChild($key);
            arrayToXml($value, $subnode);
        } else {
            $xmlData->addChild("$key", htmlspecialchars("$value"));
        }
    }
}

$jsonData = file_get_contents($apiUrl);

if ($jsonData === FALSE) {
    die("Gagal mengambil data dari API.");
}

$dataArray = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Gagal menguraikan JSON: " . json_last_error_msg());
}

$xmlData = new SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");

arrayToXml($dataArray, $xmlData);

header('Content-Type: application/xml');

echo $xmlData->asXML();
?>
