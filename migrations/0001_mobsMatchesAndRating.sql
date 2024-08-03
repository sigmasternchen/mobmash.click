create table mm_mobs
(
    id      serial
        constraint mobs_pk
            primary key,
    name    varchar(255)                        not null,
    image   varchar(255)                        not null,
    created timestamp default CURRENT_TIMESTAMP not null,
    enabled boolean   default true              not null
);

create table mm_matches
(
    id      bigserial,
    mob1fk  integer                             not null
        constraint matches_mob1_fk
            references mm_mobs,
    mob2fk  integer                             not null
        constraint matches_mob2_fk
            references mm_mobs,
    created timestamp default CURRENT_TIMESTAMP not null,
    winner  integer                             not null,
    session varchar(255)
);

create table public.mm_history_cache
(
    ratings     jsonb  not null,
    last_update bigint not null
);

CREATE VIEW mm_matches_of_mob(id, mob, opponent, won, created) AS
SELECT mm_matches.id,
       mm_matches.mob1fk     AS mob,
       mm_matches.mob2fk     AS opponent,
       mm_matches.winner = 1 AS won,
       mm_matches.created,
       mm_matches.session
FROM mm_matches
UNION
SELECT mm_matches.id,
       mm_matches.mob2fk     AS mob,
       mm_matches.mob1fk     AS opponent,
       mm_matches.winner = 2 AS won,
       mm_matches.created,
       mm_matches.session
FROM mm_matches;

CREATE VIEW mm_rating_history(ratings, last_update) AS
WITH RECURSIVE ratings_history (ratings, last_update) AS (
        WITH ratings_seed (ratings, last_update) AS (
                SELECT
                    jsonb_object_agg(id, start_value) AS ratings,
                    0::bigint AS last_update
                FROM mm_mobs
                         CROSS JOIN
                     (
                         SELECT
                             1500 AS start_value
                     ) AS start_value
                WHERE enabled
            UNION ALL
                SELECT
                    ratings,
                    last_update
                FROM mm_history_cache
        )
        SELECT
            ratings,
            last_update
        FROM ratings_seed
        WHERE
            last_update = (
                SELECT max(last_update) FROM ratings_seed
            )
    UNION ALL
        SELECT
            jsonb_set(
                jsonb_set(
                    ratings,
                    ARRAY[mob1::text],
                    to_jsonb(mob1rating)
                ),
                ARRAY[mob2::text],
                to_jsonb(mob2rating)
            ) AS ratings,
            next_match AS last_update
        FROM
            (
                SELECT
                    next_match,
                    ratings,
                    mob1,
                    mob2,
                    winner,
                    mob1rating + 32::numeric * (
                        CASE
                            WHEN winner = 1 THEN 1
                            ELSE 0
                        END::numeric - expectation
                    ) AS mob1rating,
                    mob2rating + 32::numeric * (
                        CASE
                            WHEN winner = 2 THEN 1
                            ELSE 0
                        END::numeric - expectation
                    ) AS mob2rating
                FROM
                    (
                        SELECT
                            next_match,
                            ratings,
                            mob1,
                            mob2,
                            winner,
                            mob1rating,
                            mob2rating,
                            (1::numeric /
                                (1::numeric + power(
                                    10::numeric,
                                    (mob1rating - mob2rating) / 400::numeric
                                ))
                            ) AS expectation
                        FROM
                        (
                            SELECT
                                next_match.id AS next_match,
                                next_match.ratings AS ratings,
                                mm_matches.mob1fk AS mob1,
                                mm_matches.mob2fk AS mob2,
                                mm_matches.winner AS winner,
                                cast(next_match.ratings->cast(mm_matches.mob2fk AS varchar) AS numeric) AS mob1rating,
                                cast(next_match.ratings->cast(mm_matches.mob1fk AS varchar) AS numeric) AS mob2rating
                            FROM
                                (
                                    SELECT
                                        mm_matches.id,
                                        ratings_history.ratings
                                    FROM ratings_history
                                    CROSS JOIN mm_matches
                                    INNER JOIN mm_mobs AS mob
                                        ON mob.id = mm_matches.mob1fk
                                    INNER JOIN mm_mobs AS opponent
                                        ON opponent.id = mm_matches.mob1fk
                                    WHERE mm_matches.id > ratings_history.last_update
                                        AND mob.enabled
                                        AND opponent.enabled
                                    ORDER BY mm_matches.id ASC
                                    LIMIT 1
                                ) AS next_match
                            INNER JOIN mm_matches
                                ON mm_matches.id = next_match.id
                        ) AS match_with_ratings
                    ) AS expectation
                ) AS new_ratings
)
SELECT * from ratings_history;

CREATE VIEW mm_current_rating(mob, rating) AS
SELECT
    cast(key as numeric) as mob,
    cast(value as numeric) as rating
FROM jsonb_each(
    (
        SELECT
            ratings
        FROM mm_rating_history
        WHERE last_update = (
            SELECT max(last_update)
            FROM mm_rating_history
        )
    )
);

CREATE VIEW mm_rating_trends (mob, rating, "date", id) AS
SELECT
    key AS mob,
    value AS rating,
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
            FROM mm_history_cache AS history
            INNER JOIN mm_matches AS matches
                ON history.last_update = matches.id
        ) AS dates
        GROUP BY "date"
    ) AS key_dates
    INNER JOIN mm_history_cache AS history
        ON key_dates.id = history.last_update
) AS ratings_at_key_date,
jsonb_each(ratings_at_key_date.ratings) AS ratings(key, value);
