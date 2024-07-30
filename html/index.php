<?php

require_once __DIR__ . "/../core.php";
require_once __DIR__ . "/../lib/pairing.php";

session_start();

$pairing = makeInitialPairing(session_id());

$left = $pairing[0];
$right = $pairing[1];

$title = "Test";
$content = function() use ($left, $right) {
    include __DIR__ . "/../view/fragments/mobSelection.php";
};

include __DIR__ . "/../view/layout.php";