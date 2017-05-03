<?php

// go through every page and download a list of available comics
$page = 1;
$urlList = [];
while (1) {
    $url = 'http://www.commitstrip.com/en/page/' . $page;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $result = curl_exec($ch);

    if (!curl_errno($ch)) {
        $info = curl_getinfo($ch);
        if ($info['http_code'] != 200) {
            curl_close($ch);
            break;
        }
    }
    curl_close($ch);

    $dom = new DOMDocument();
    $dom->loadHTML($result);

    $xpath = new DomXPath($dom);
    $class = 'excerpt';
    $divs = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]/section/a/@href");

    foreach ($divs as $div) {
        $urlList[] = $div->value;
    }
    $page++;
}
