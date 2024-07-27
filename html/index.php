<?php

require_once __DIR__ . "/../core.php";
require_once __DIR__ . "/../lib/rating.php";

session_start();

$left = getRandomMob();
$right = getRandomMob();

$left["rating"] = getEloForMob($left["id"]);
$right["rating"] = getEloForMob($right["id"]);

$title = "Test";
$content = function() use ($left, $right) {
    include __DIR__ . "/../view/fragments/mobSelection.php";
};

include __DIR__ . "/../view/layout.php";