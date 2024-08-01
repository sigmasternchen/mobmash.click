<?php

require_once __DIR__ . "/../core.php";
require_once __DIR__ . "/../lib/pairing.php";
require_once __DIR__ . "/../lib/rating.php";
require_once __DIR__ . "/../lib/security.php";

function renderChoice(): void {
    [$left, $right] = [$_SESSION["left"], $_SESSION["right"]];

    $csrfToken = $_SESSION["csrfToken"];
    $title = "Test";
    $content = function() use ($left, $right, $csrfToken) {
        include __DIR__ . "/../view/fragments/mobSelection.php";
    };

    include __DIR__ . "/../view/layout.php";
}

function reload(): void {
    header("LOCATION: /");
    http_send_status(303);
}

function newPairing(): array {
    return makeInitialPairing(session_id());
}

const LEFT = 0;
const RIGHT = 1;

function voteAndNextPairing(int $winner): array {
    if ($_POST["csrfToken"] != $_SESSION["csrfToken"]) {
        return [$_SESSION["left"], $_SESSION["right"]];
    }

    addMatch($_SESSION["left"]["id"], $_SESSION["right"]["id"], $winner, session_id());

    $winnerMob = ($winner == 0) ? $_SESSION["left"] : $_SESSION["right"];

    [$left, $right] = makeFollowUpPairing(session_id(), $winnerMob["id"]);
    if (($winner == LEFT && $left["id"] != $winnerMob["id"]) ||
        ($winner == RIGHT && $right["id"] != $winnerMob["id"])
    ) {
        [$left, $right] = [$right, $left];
    }

    return [$left, $right];
}

session_start();

[$_SESSION["left"], $_SESSION["right"], $render] = match (true) {
    isset($_GET["new"]), !isset($_SESSION["left"]) => [...newPairing(), false],
    isset($_GET["left"]) => [...voteAndNextPairing(LEFT), false],
    isset($_GET["right"]) => [...voteAndNextPairing(RIGHT), false],
    default => [$_SESSION["left"], $_SESSION["right"], true],
};

if ($render) {
    renderChoice();
} else {
    $_SESSION["csrfToken"] = makeCcrfToken();
    reload();
}
