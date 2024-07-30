<?php

require_once __DIR__ . "/database.php";

function getEloForMob(int $mob): int {
    global $pdo;
    $query = $pdo->prepare("SELECT * FROM mm_current_rating WHERE mob = ?");
    $query->execute([$mob]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result["rating"];
}