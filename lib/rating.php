<?php

require_once __DIR__ . "/database.php";

function getEloForMob(int $mob): int {
    global $pdo;
    $query = $pdo->prepare("SELECT * FROM mm_current_rating WHERE mob = ?");
    $query->execute([$mob]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result["rating"];
}

function addMatch(int $mob1, int $mob2, int $winner, string $session): void {
    global $pdo;
    $query = $pdo->prepare("INSERT INTO mm_matches (mob1fk, mob2fk, winner, session) VALUES (?, ?, ?, ?)");
    $query->execute([$mob1, $mob2, $winner, $session]);
}