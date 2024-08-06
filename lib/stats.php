<?php

require_once __DIR__ . '/rating.php';

function getStatistics(): array {
    global $pdo;

    $query = $pdo->query(<<<EOF
        SELECT 
            max(c) AS max, 
            min(c) AS min, 
            avg(c) AS avg,
            count(c) AS voters
        FROM (
            SELECT 
                count(*) AS c
            FROM mm_matches 
            WHERE session IS NOT NULL GROUP BY session
        ) AS c;
    EOF
    );

    $query->setFetchMode(PDO::FETCH_ASSOC);

    $result = $query->fetch();

    $query = $pdo->query("SELECT count(*) AS votes FROM mm_matches");
    $query->setFetchMode(PDO::FETCH_ASSOC);

    $result = array_merge($result, $query->fetch());

    $query = $pdo->query("SELECT count(*) AS mobs FROM mm_mobs");
    $query->setFetchMode(PDO::FETCH_ASSOC);

    $result = array_merge($result, $query->fetch());

    $query = $pdo->query(<<<EOF
        SELECT 
            count(*) AS maxed_out
        FROM (
            SELECT 
                count(*) AS c 
            FROM mm_matches 
            WHERE session IS NOT NULL
            GROUP BY session
        ) AS c
        WHERE c.c >= power((SELECT count(*) FROM mm_mobs), 2);
    EOF
    );
    $query->setFetchMode(PDO::FETCH_ASSOC);

    $result = array_merge($result, $query->fetch());

    return $result;
}

function getMobStats(): array {
    $mobs = getMobsWithMetaData();

    return [
        "highest_rating" => array_reduce($mobs,
            fn($current, $mob) => $mob["rating"] > $current["rating"] ? $mob : $current,
            $mobs[0]
        ),
        "lowest_rating" => array_reduce($mobs,
            fn($current, $mob) => $mob["rating"] < $current["rating"] ? $mob : $current,
            $mobs[0]
        ),
        "most_matches" => array_reduce($mobs,
            fn($current, $mob) => $mob["matches"] > $current["matches"] ? $mob : $current,
            $mobs[0]
        ),
        "least_matches" => array_reduce($mobs,
            fn($current, $mob) => $mob["matches"] < $current["matches"] ? $mob : $current,
            $mobs[0]
        ),
        "most_wins" => array_reduce($mobs,
            fn($current, $mob) => $mob["wins"] > $current["wins"] ? $mob : $current,
            $mobs[0]
        ),
        "least_wins" => array_reduce($mobs,
            fn($current, $mob) => $mob["losses"] > $current["losses"] ? $mob : $current,
            $mobs[0]
        ),
    ];
}