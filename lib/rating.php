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
    $query = $pdo->prepare("INSERT INTO mm_matches (mob1fk, mob2fk, winner, session) VALUES (?, ?, ?, ?) RETURNING id");
    $query->execute([$mob1, $mob2, $winner, $session]);

    $result = $query->fetch(PDO::FETCH_ASSOC);

    auditLog(AUDIT_EVENT_MATCH_ADDED, session_id(), $result["id"]);
}

function getMobsWithMetaData($orderBy = "rating", $direction = "DESC"): array {
    global $pdo;
    $query = $pdo->prepare(<<<EOF
        SELECT
            row_number() OVER () AS position,
            *
        FROM (
            SELECT
                id,
                name,
                image,
                created,
                matches,
                wins,
                matches - wins AS losses,
                rating
            FROM mm_mobs
            INNER JOIN (
                SELECT 
                    mob,
                    count(*) AS matches,
                    sum(CASE WHEN won THEN 1 ELSE 0 END) AS wins
                FROM mm_matches_of_mob
                GROUP BY mob
            ) AS match_metadata
                ON match_metadata.mob = mm_mobs.id
            INNER JOIN mm_current_rating AS rating
                ON rating.mob = mm_mobs.id
            WHERE enabled
            ORDER BY rating DESC
        ) AS with_rating
    EOF
        . " ORDER BY " . $orderBy . " " . $direction
    );
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getRatingTrends(): array {
    global $pdo;
    $query = $pdo->prepare(<<<EOF
        SELECT * FROM mm_rating_trends
        ORDER BY date ASC;
    EOF
    );
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $trends = [];
    foreach ($results as $result) {
        $trendsForMob = [];
        if (isset($trends[$result['mob']])) {
            $trendsForMob = $trends[$result['mob']];
        }
        $trendsForMob[] = $result;
        $trends[$result['mob']] = $trendsForMob;
    }

    return $trends;
}