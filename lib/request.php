<?php

require_once __DIR__ . "/../config.php";

const USER_AGENT = "MobMash Updater/" . VERSION . " (+https://github.com/overflowerror/mobmash.click; contact: " . CONTACT_EMAIL . ")";

function get(string $url): string {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
