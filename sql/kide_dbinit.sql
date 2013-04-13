CREATE TABLE users (
    username varchar(30) PRIMARY KEY NOT NULL,
    password varchar(30) NOT NULL,
    email varchar(50) NOT NULL
);

CREATE TABLE kide (
    id varchar(50) PRIMARY KEY NOT NULL,
    "time" timestamp without time zone NOT NULL,
    c1nn varchar(2) REFERENCES particle_class,
    c3nn varchar(2) REFERENCES particle_class,
    c5nn varchar(2) REFERENCES particle_class,
    nw3nn varchar(2) REFERENCES particle_class,
    nw5nn varchar(2) REFERENCES particle_class,
    bayes varchar(2) REFERENCES particle_class,
    dmax real NOT NULL,
    ar real NOT NULL,
    CHECK (ar > 0 AND ar < 1),
    ar_noholes real NOT NULL,
    CHECK (ar_noholes > 0 AND ar_noholes < 1),
    asprat real NOT NULL,
    n_corners integer,
    site varchar(3) REFERENCES ARM_site,
    filtered integer REFERENCES filter_flag
);

CREATE TABLE filter_flag (
    id SERIAL PRIMARY KEY,
    message varchar(20)
);

CREATE TABLE man_classification (
    kide_id varchar(50) NOT NULL REFERENCES kide ON DELETE CASCADE,
    class1 varchar(2) NOT NULL REFERENCES particle_class,
    class2 varchar(2) REFERENCES particle_class,
    classified_by varchar(30) NOT NULL REFERENCES users ON DELETE CASCADE,
    quality boolean
);

CREATE TABLE particle_class (
    id varchar(2) PRIMARY KEY,
    classname varchar(20) NOT NULL
);

INSERT INTO particle_class VALUES
    ('B','bullet'),
    ('P','plate'),
    ('C','column'),
    ('I','irregular'),
    ('PA','Plate agg.'),
    ('CA','column agg.'),
    ('R','rosette'),
    ('RA','rosette agg.');

CREATE TABLE ARM_site (
    id varchar(3) PRIMARY KEY,
    fullname varchar(50)
);

INSERT INTO ARM_site VALUES
    ('SGP', 'Southern Great Plains'),
    ('NSA', 'North Slope of Alaska'),
    ('TWP', 'Tropical Western Pacific'),
    ('AMF', 'ARM Mobile Facility'),
    ('AAF', 'ARM Aerial Facility'),
    ('oth', 'Other');

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
