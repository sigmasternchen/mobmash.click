<?php

require_once __DIR__ . '/../../core.php';
require_once __DIR__ . '/../../lib/rating.php';

$validOrderColumns = [
    "position",
    "name",
    "rating",
    "matches",
    "wins",
    "losses"
];

$orderColumn = $_GET["order"];
if (!in_array($orderColumn, $validOrderColumns)) {
    $orderColumn = "position";
}

$orderDirection = $_GET["direction"] == "desc" ? "desc" : "asc";

$mobs = getMobsWithMetaData($orderColumn, $orderDirection);
$trends = getRatingTrends();

if (isset($_GET["ajax"])) {
    require __DIR__ . '/../../view/fragments/mobList.php';
} else {
    $title = "MobMash - Results";
    $content = function () use ($mobs, $trends) {
        require __DIR__ . '/../../view/pages/results.php';
    };

    require_once __DIR__ . '/../../view/layout.php';
}