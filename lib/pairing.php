<?php

function getRandomMob(): array {
    global $pdo;
    $result = $pdo->query("SELECT * FROM mm_mobs WHERE enabled = true");
    $result = $result->fetchAll(PDO::FETCH_ASSOC);
    return $result[array_rand($result)];
}

function findPair(string $session, int $current): array|false {
    global $pdo;

    error_log($current);

    // language=sql
    $query = $pdo->prepare(<<<EOF
            WITH possible_pairings AS (
                SELECT
                    pairings.mob,
                    mob_rating.rating AS mob_rating,
                    pairings.opponent,
                    opponent_rating.rating AS opponent_rating
                FROM (
                    (
                        SELECT
                            mob.id AS mob,
                            opponent.id AS opponent
                        FROM mm_mobs mob
                        CROSS JOIN mm_mobs opponent
                        WHERE
                                mob.id != opponent.id
                            AND mob.enabled
                            AND opponent.enabled
                    )
                    EXCEPT
                        SELECT
                            mob,
                            opponent
                        FROM mm_matches_of_mob
                        WHERE session = ?
                ) AS pairings
                INNER JOIN mm_current_rating mob_rating 
                    ON mob_rating.mob = pairings.mob
                INNER JOIN mm_current_rating opponent_rating 
                    ON opponent_rating.mob = pairings.opponent
            )
            SELECT
                mob.id AS mob,
                mob.name AS mob_name,
                mob.image AS mob_image,
                pairings_with_difference.mob_rating AS mob_rating,
                opponent.id AS opponent,
                opponent.name AS opponent_name,
                opponent.image AS opponent_image,
                pairings_with_difference.opponent_rating AS opponent_rating,
                pairings_with_difference.difference AS rating_difference
            FROM
            (
                SELECT
                    *,
                    abs(mob_rating - opponent_rating) AS difference
                FROM possible_pairings
            ) AS pairings_with_difference
            INNER JOIN mm_mobs AS mob 
                ON pairings_with_difference.mob = mob.id
            INNER JOIN mm_mobs AS opponent 
                ON pairings_with_difference.opponent = opponent.id
            ORDER BY 
                (pairings_with_difference.mob = ?) DESC, 
                pairings_with_difference.difference ASC
            LIMIT 1;
        EOF);
    $query->execute([$session, $current]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
        return false;
    } else {
        return [
            [
                "id" => $result["mob"],
                "name" => $result["mob_name"],
                "image" => $result["mob_image"],
                "rating" => $result["mob_rating"],
            ],
            [
                "id" => $result["opponent"],
                "name" => $result["opponent_name"],
                "image" => $result["opponent_image"],
                "rating" => $result["opponent_rating"],
            ]
        ];
    }
}

function makeInitialPairing(string $session): array {
    $current = getRandomMob()["id"];
    return findPair($session, $current);
}

function makeFollowUpPairing(string $session, int $winner): array {
    return findPair($session, $winner);
}