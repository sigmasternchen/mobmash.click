<?php

require_once __DIR__ . '/../../core.php';

$title = "MobMash - Privacy Notice";
$content = function () {
    require __DIR__ . '/../../view/pages/privacy.php';
};

require_once __DIR__ . '/../../view/layout.php';