<?php

require_once __DIR__ . "/database.php";

function getRandomMob(): array {
    global $pdo;
    $result = $pdo->query("SELECT * FROM mm_mobs WHERE enabled = true");
    $result = $result->fetchAll(PDO::FETCH_ASSOC);
    return $result[array_rand($result)];
}

function getEloForMob(int $mob): int {
    global $pdo;
    $query = $pdo->prepare("SELECT * FROM mm_current_rating WHERE mob = ?");
    $query->execute([$mob]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result["rating"];
}