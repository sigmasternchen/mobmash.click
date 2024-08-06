<?php

require_once __DIR__ . '/../../core.php';

require_once __DIR__ . '/../../lib/stats.php';

$stats = getStatistics();
$mobStats = getMobStats();


$title = "MobMash - About";
$content = function () use ($stats, $mobStats) {
    require __DIR__ . '/../../view/pages/about.php';
};

require_once __DIR__ . '/../../view/layout.php';