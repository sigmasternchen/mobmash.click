<?php

require_once __DIR__ . '/../../core.php';
require_once __DIR__ . '/../../lib/rating.php';

$mobs = getMobsWithMetaData();

$title = "MobMash - Results";
$content = function () use ($mobs) {
    require __DIR__ . '/../../view/pages/results.php';
};

require_once __DIR__ . '/../../view/layout.php';