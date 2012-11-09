CREATE TYPE nn AS ENUM ('B', 'P', 'C', 'I', 'PA', 'CA', 'R', 'RA');

CREATE TYPE nwnn AS ENUM ('B', 'P', 'C', 'I', 'PA', 'CA', 'R', 'RA', 'ucl');

CREATE TYPE bayesian AS ENUM ('B', 'P', 'C', 'I', 'PA', 'CA', 'R', 'RA', 'NaN');

CREATE TYPE saitti AS ENUM ('AAF', 'AMF', 'NSA', 'SGP', 'TWP', 'other');

CREATE TABLE users (
    username varchar PRIMARY KEY NOT NULL,
    password varchar NOT NULL
);

CREATE TABLE kide (
    id varchar(50) PRIMARY KEY NOT NULL,
    "time" timestamp without time zone NOT NULL,
    c1nn nn,
    c3nn nn,
    c5nn nn,
    nw3nn nwnn,
    nw5nn nwnn,
    bayes bayesian,
    dmax real NOT NULL,
    ar real NOT NULL,
    CHECK (ar > 0 AND ar < 1),
    ar_noholes real NOT NULL,
    CHECK (ar_noholes > 0 AND ar_noholes < 1),
    asprat real NOT NULL,
    n_corners integer NOT NULL,
    site saitti
);

CREATE TABLE manual_classification (
    kide_id varchar(50) NOT NULL REFERENCES kide ON DELETE CASCADE,
    class1 nn NOT NULL,
    class2 nn,
    classified_by varchar NOT NULL REFERENCES users ON DELETE CASCADE,
    quality boolean
);

CREATE OR REPLACE FUNCTION round_minutes(timestamp without time zone, integer DEFAULT 1) RETURNS timestamp without time zone
    AS $$ SELECT date_trunc('hour', $1) + cast(($2::varchar||' min') AS interval) * round(date_part('minute',$1)::float / cast($2 AS float)) $$
    LANGUAGE SQL
    IMMUTABLE;

CREATE OR REPLACE FUNCTION round_seconds(timestamp without time zone, integer DEFAULT 1) RETURNS timestamp without time zone
    AS $$ SELECT date_trunc('minute', $1) + cast(($2::varchar||' sec') AS interval) * round(date_part('second',$1)::float / cast($2 AS float)) $$
    LANGUAGE SQL
    IMMUTABLE;

CREATE OR REPLACE FUNCTION area(real, real) RETURNS double precision
    AS $$ SELECT $1 * pi() * 0.25 * $2 ^ 2 $$
    LANGUAGE SQL
    IMMUTABLE
    RETURNS NULL ON NULL INPUT;
