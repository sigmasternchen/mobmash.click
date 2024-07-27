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

CREATE VIEW mm_matches_of_mob(id, mob, opponent, won, created) AS
SELECT mm_matches.id,
       mm_matches.mob1fk     AS mob,
       mm_matches.mob2fk     AS opponent,
       mm_matches.winner = 1 AS won,
       mm_matches.created
FROM mm_matches
UNION
SELECT mm_matches.id,
       mm_matches.mob2fk     AS mob,
       mm_matches.mob1fk     AS opponent,
       mm_matches.winner = 2 AS won,
       mm_matches.created
FROM mm_matches;

CREATE VIEW mm_rating(mob, rating, last_update) AS
WITH RECURSIVE rating(mob, rating, last_update) AS (
    SELECT mm_mobs.id AS mob,
           1500.0    AS rating,
           0::bigint AS last_update
    FROM mm_mobs
    UNION
    SELECT expectation.mob,
           expectation.own_rating + 32::numeric * (
               CASE
                   WHEN expectation.won THEN 1
                   ELSE 0
               END::numeric - expectation.expectation) AS rating,
            expectation.id                             AS last_update
    FROM (
        WITH newest_rating(mob, rating, last_update) AS (
            SELECT
                rating_with_row_number.mob,
                rating_with_row_number.rating,
                rating_with_row_number.last_update,
                rating_with_row_number.row_number
            FROM (
                SELECT rating_for_row_number.mob,
                    rating_for_row_number.rating,
                    rating_for_row_number.last_update,
                    row_number()
                        OVER (
                            PARTITION BY rating_for_row_number.mob
                            ORDER BY rating_for_row_number.last_update DESC
                        ) AS row_number
                 FROM rating rating_for_row_number
            ) rating_with_row_number
            WHERE rating_with_row_number.row_number = 1)
        SELECT
            next_match.id,
            mm_matches_of_mob.mob,
            mm_matches_of_mob.won,
            own_rating.rating      AS own_rating,
            opponent_rating.rating AS opponent_rating,
            (1::numeric /
                (
                    1::numeric +
                        power(
                            10::numeric,
                            (
                                opponent_rating.rating - own_rating.rating
                            ) / 400::numeric
                        )
                )
            ) AS expectation
        FROM (
            SELECT
                min(matches_for_next_match.id) AS id,
                matches_for_next_match.mob
            FROM mm_matches_of_mob matches_for_next_match
            JOIN newest_rating own_rating_1
                ON matches_for_next_match.mob = own_rating_1.mob
            WHERE matches_for_next_match.id > own_rating_1.last_update
            GROUP BY matches_for_next_match.mob
        ) next_match
        JOIN mm_matches_of_mob
            ON next_match.id = mm_matches_of_mob.id AND
               next_match.mob = mm_matches_of_mob.mob
        JOIN newest_rating own_rating
            ON mm_matches_of_mob.mob = own_rating.mob
        JOIN newest_rating opponent_rating
            ON mm_matches_of_mob.opponent = opponent_rating.mob
    ) expectation
)
SELECT mob,
       rating,
       last_update
FROM rating;


