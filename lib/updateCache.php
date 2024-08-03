<?php

function updateCache(): void {
    global $pdo;

    $query = $pdo->query(<<<EOF
        INSERT INTO mm_history_cache
        SELECT 
            ratings, 
            last_update
        FROM mm_rating_history
        WHERE last_update > (
            SELECT max(last_update) 
            FROM (
                    SELECT last_update
                    FROM mm_history_cache 
                UNION ALL
                    SELECT 0
            ) AS with_default
        )
    EOF
    );
    $query->execute();
}