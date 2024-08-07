CREATE OR REPLACE VIEW mm_rating_trends (mob, rating, "date", id) AS
WITH complete_history (ratings, last_update) AS (
    SELECT * FROM mm_history_cache
    UNION SELECT * FROM mm_rating_history
)
SELECT
    cast(key AS numeric) AS mob,
    cast(value AS numeric) rating,
    "date",
    id
FROM (
    SELECT
        id,
        ratings,
        "date"
    FROM (
        SELECT
            max(id) AS id,
            "date"
        FROM (
            SELECT
                last_update AS id,
                date(matches.created) AS "date"
            FROM complete_history AS history
            INNER JOIN mm_matches AS matches
                ON history.last_update = matches.id
        ) AS dates
        GROUP BY "date"
    ) AS key_dates
    INNER JOIN complete_history AS history
    ON key_dates.id = history.last_update
) AS ratings_at_key_date,
jsonb_each(ratings_at_key_date.ratings) AS ratings(key, value);